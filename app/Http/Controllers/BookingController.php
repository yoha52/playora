<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query()->with([
            'court:id,name,ground_id,category_id' => [
                'ground:id,name',
                'category:id,name',
            ],
            'user:id,name,email,contact_no',
        ]);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('contact_no', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        $courts = Court::query()->active()->with('ground')->get();

        return view('bookings.index', compact('bookings', 'courts'));
    }

    public function create(Request $request)
    {
        $courts = Court::query()
            ->active()
            ->with([
                'media',
                'ground',
                'category',
            ])
            ->get();
        $selectedCourt = $request->filled('court_id') ? Court::find($request->court_id) : null;
        $selectedDate = $request->filled('date') ? $request->date : today()->format('Y-m-d');

        return view('bookings.create', compact('courts', 'selectedCourt', 'selectedDate'));
    }

    public function store(BookingRequest $request)
    {
        $court = Court::findOrFail($request->court_id);
        $duration = Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time));
        $totalAmount = $duration * $court->rate_per_hour;

        $data = $request->validated();
        $data['total_amount'] = $totalAmount;

        Booking::create($data);

        return redirect()->route('bookings.index')
            ->with('success', __('general.booking_created'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user:id,name,email,contact_no',
            'court' => [
                'media',
                'ground',
                'category' => [
                    'media',
                ],
            ],
        ]);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if ($booking->status === BookingStatus::Cancelled) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', __('general.cannot_edit_cancelled_booking'));
        }

        $booking->load('user:id,name,email,contact_no');
        $courts = Court::query()->active()->with(['ground', 'category'])->get();

        return view('bookings.edit', compact('booking', 'courts'));
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        if ($booking->status === BookingStatus::Cancelled) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', __('general.cannot_edit_cancelled_booking'));
        }

        $court = Court::findOrFail($request->court_id);
        $duration = Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time));
        $totalAmount = $duration * $court->rate_per_hour;

        $data = $request->validated();
        $data['total_amount'] = $totalAmount;

        $booking->update($data);

        return redirect()->route('bookings.index')
            ->with('success', __('general.booking_updated'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', __('general.booking_deleted'));
    }

    public function receivePayment(Request $request, Booking $booking)
    {
        if (! $booking->canReceivePayment()) {
            return back()->with('error', __('general.cannot_receive_payment'));
        }

        $balanceDue = $booking->getBalanceDue();

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:'.$balanceDue],
        ]);

        $booking->update([
            'paid_amount' => $booking->paid_amount + $request->amount,
        ]);

        return back()->with('success', __('general.payment_received'));
    }

    public function cancel(Booking $booking)
    {
        if (! $booking->canCancel()) {
            return back()->with('error', __('general.cannot_cancel_booking'));
        }

        $booking->update([
            'status' => BookingStatus::Cancelled,
        ]);

        return back()->with('success', __('general.booking_cancelled'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'court_id' => ['required', 'exists:courts,id'],
            'date' => ['required', 'date'],
        ]);

        $court = Court::findOrFail($request->court_id);
        $date = Carbon::parse($request->date);

        $existingBookings = Booking::query()
            ->forCourt($court->id)
            ->forDate($date)
            ->notCancelled()
            ->orderBy('start_time')
            ->get(['start_time', 'end_time']);

        $openingTime = Carbon::parse($court->opening_time);
        $closingTime = Carbon::parse($court->closing_time);

        $slots = [];
        $currentTime = $openingTime->copy();

        while ($currentTime->lessThan($closingTime)) {
            $slotEnd = $currentTime->copy()->addHour();

            if ($slotEnd->greaterThan($closingTime)) {
                break;
            }

            $isBooked = $existingBookings->contains(function ($booking) use ($currentTime, $slotEnd) {
                $bookingStart = Carbon::parse($booking->start_time);
                $bookingEnd = Carbon::parse($booking->end_time);

                return $currentTime->lt($bookingEnd) && $slotEnd->gt($bookingStart);
            });

            $slots[] = [
                'start_time' => $currentTime->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'display_time' => $currentTime->format('g:i A'),
                'available' => ! $isBooked,
            ];

            $currentTime->addHour();
        }

        return response()->json([
            'court' => [
                'id' => $court->id,
                'name' => $court->name,
                'rate_per_hour' => $court->rate_per_hour,
                'opening_time' => Carbon::parse($court->opening_time)->format('g:i A'),
                'closing_time' => Carbon::parse($court->closing_time)->format('g:i A'),
            ],
            'date' => $date->format('Y-m-d'),
            'slots' => $slots,
        ]);
    }
}
