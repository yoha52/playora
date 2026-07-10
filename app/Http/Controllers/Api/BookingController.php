<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function availableSlots(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => ['required', 'date', 'after_or_equal:today'],
            'court_id' => ['required', 'integer', 'exists:courts,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $date = Carbon::parse($request->date);

        $court = Court::find($request->court_id);

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
                'start_time' => formatTime($currentTime),
                'end_time' => formatTime($slotEnd),
                'available' => ! $isBooked,
            ];

            $currentTime->addHour();
        }

        return response()->json([
            'court' => [
                'id' => $court->id,
                'name' => $court->name,
                'rate_per_hour' => (float) $court->rate_per_hour,
                'opening_time' => formatTime($court->opening_time),
                'closing_time' => formatTime($court->closing_time),
            ],
            'date' => formatDate($date),
            'slots' => $slots,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'court_id' => ['required', 'exists:courts,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string', 'max:1000'],

            'payment_intent_id' => ['required', 'string', 'min:1', 'max:255'],
            'card_brand' => ['sometimes', 'nullable', 'max:50'],
            'card_last_digits' => ['sometimes', 'nullable', 'max_digits:4'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();
        $court = Court::findOrFail($validated['court_id']);

        // Check for overlapping bookings
        $hasOverlap = Booking::query()
            ->forCourt($validated['court_id'])
            ->forDate($validated['date'])
            ->overlapping($validated['start_time'], $validated['end_time'])
            ->notCancelled()
            ->exists();

        if ($hasOverlap) {
            return response()->json([
                'message' => __('general.booking_overlap_error'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Check operating hours
        $openingTime = Carbon::parse($court->opening_time)->format('H:i');
        $closingTime = Carbon::parse($court->closing_time)->format('H:i');

        if ($validated['start_time'] < $openingTime || $validated['end_time'] > $closingTime) {
            return response()->json([
                'message' => __('general.booking_outside_hours_error'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Calculate total amount
        $duration = Carbon::parse($validated['start_time'])->diffInHours(Carbon::parse($validated['end_time']));
        $totalAmount = $duration * $court->rate_per_hour;

        $booking = Booking::create([
            'court_id' => $validated['court_id'],
            'user_id' => $request->user()->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'paid_amount' => 0,
            'total_amount' => $totalAmount,
            'paid_amount' => $totalAmount,
            'status' => BookingStatus::Confirmed,
            'notes' => $validated['notes'] ?? null,
        ]);

        $booking->transactions()->create([
            'payment_intent_id' => $validated['payment_intent_id'],
            'amount' => $totalAmount,
            'card_brand' => $validated['card_brand'] ?? null,
            'card_last_digits' => $validated['card_last_digits'] ?? null,
        ]);

        return response()->json([
            'message' => __('general.booking_created'),
            'data' => [
                'booking_id' => $booking->id,
            ],
        ], Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => ['required', 'int', 'exists:bookings,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $booking = Booking::query()
            ->with([
                'user:id,name,email,contact_no',
                'court:id,name,ground_id,category_id,rate_per_hour' => [
                    'ground:id,name,address,latitude,longitude' => [
                        'media',
                    ],
                    'category:id,name',
                ],
            ])
            ->findOrFail($request->integer('booking_id'));

        return response()->json([
            'data' => new BookingResource($booking),
        ]);
    }

    public function upcoming(Request $request): AnonymousResourceCollection
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'user:id,name,email,contact_no',
                'court:id,name,ground_id,category_id,rate_per_hour' => [
                    'category:id,name',
                    'ground:id,name,address,latitude,longitude' => [
                        'media',
                    ],
                ],
            ])
            ->where(function (Builder $query) {
                $query->where('date', '>', today())
                    ->orWhere(function (Builder $q) {
                        $q->where('date', today())
                            ->where('start_time', '>', now()->format('H:i:s'));
                    });
            })
            ->notCancelled()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return BookingResource::collection($bookings);
    }

    public function completed(Request $request): AnonymousResourceCollection
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'user:id,name,email,contact_no',
                'court:id,name,ground_id,category_id,rate_per_hour' => [
                    'category:id,name',
                    'ground:id,name,address,latitude,longitude' => [
                        'media',
                    ],
                ],
            ])
            ->where(function (Builder $query) {
                $query->where('status', BookingStatus::Completed)
                    ->orWhere(function (Builder $q) {
                        $q->where('date', '<', today());
                    })
                    ->orWhere(function (Builder $q) {
                        $q->where('date', today())
                            ->where('end_time', '<=', now()->format('H:i:s'));
                    });
            })
            ->notCancelled()
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();

        return BookingResource::collection($bookings);
    }
}
