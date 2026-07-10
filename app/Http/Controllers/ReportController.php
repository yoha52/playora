<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Court;
use App\Models\Ground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dueBookings(Request $request)
    {
        $courts = Court::query()->active()->with('ground:id,name')->get();
        $hasFilters = $request->filled(['from_date', 'to_date']);

        if (! $hasFilters) {
            return view('reports.due-bookings', [
                'bookings' => collect(),
                'courts' => $courts,
                'totals' => ['total_amount' => 0, 'paid_amount' => 0, 'due_amount' => 0],
                'hasFilters' => false,
            ]);
        }

        $query = Booking::query()
            ->with([
                'user:id,name,email,contact_no',
                'court:id,name,ground_id,category_id' => [
                    'ground:id,name',
                    'category:id,name',
                ],
            ])
            ->withDue()
            ->whereDate('date', '>=', $request->from_date)
            ->whereDate('date', '<=', $request->to_date);

        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        $bookings = $query->latest('date')->paginate(20);

        $totals = [
            'total_amount' => $query->sum('total_amount'),
            'paid_amount' => $query->sum('paid_amount'),
            'due_amount' => $query->sum(DB::raw('total_amount - paid_amount')),
        ];

        return view('reports.due-bookings', compact('bookings', 'courts', 'totals', 'hasFilters'));
    }

    public function groundStats(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $hasFilters = $request->filled(['from_date', 'to_date']);

        if (! $hasFilters) {
            return view('reports.ground-stats', [
                'stats' => collect(),
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'hasFilters' => false,
            ]);
        }

        $stats = Ground::query()
            ->withCount([
                'courts',
                'bookings as total_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate);
                },
                'bookings as confirmed_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Confirmed);
                },
                'bookings as completed_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Completed);
                },
                'bookings as cancelled_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Cancelled);
                },
            ])
            ->withSum([
                'bookings as total_revenue' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', '!=', BookingStatus::Cancelled);
                },
            ], 'total_amount')
            ->withSum([
                'bookings as collected_amount' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', '!=', BookingStatus::Cancelled);
                },
            ], 'paid_amount')
            ->active()
            ->get();

        return view('reports.ground-stats', compact('stats', 'fromDate', 'toDate', 'hasFilters'));
    }

    public function courtStats(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $groundId = $request->ground_id;
        $grounds = Ground::query()->active()->get(['id', 'name']);
        $hasFilters = $request->filled(['from_date', 'to_date']);

        if (! $hasFilters) {
            return view('reports.court-stats', [
                'stats' => collect(),
                'grounds' => $grounds,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'groundId' => $groundId,
                'hasFilters' => false,
            ]);
        }

        $query = Court::query()
            ->active()
            ->with(['ground:id,name', 'category:id,name'])
            ->withCount([
                'bookings as total_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate);
                },
                'bookings as confirmed_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Confirmed);
                },
                'bookings as completed_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Completed);
                },
                'bookings as cancelled_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Cancelled);
                },
            ])
            ->withSum([
                'bookings as total_revenue' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', '!=', BookingStatus::Cancelled);
                },
            ], 'total_amount')
            ->withSum([
                'bookings as collected_amount' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', '!=', BookingStatus::Cancelled);
                },
            ], 'paid_amount');

        if ($groundId) {
            $query->where('ground_id', $groundId);
        }

        $stats = $query->get();

        return view('reports.court-stats', compact('stats', 'grounds', 'fromDate', 'toDate', 'groundId', 'hasFilters'));
    }

    public function categoryStats(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $hasFilters = $request->filled(['from_date', 'to_date']);

        if (! $hasFilters) {
            return view('reports.category-stats', [
                'stats' => collect(),
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'hasFilters' => false,
            ]);
        }

        $stats = Category::query()
            ->withCount([
                'courts',
                'bookings as total_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate);
                },
                'bookings as confirmed_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Confirmed);
                },
                'bookings as completed_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Completed);
                },
                'bookings as cancelled_bookings' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', BookingStatus::Cancelled);
                },
            ])
            ->withSum([
                'bookings as total_revenue' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', '!=', BookingStatus::Cancelled);
                },
            ], 'total_amount')
            ->withSum([
                'bookings as collected_amount' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('status', '!=', BookingStatus::Cancelled);
                },
            ], 'paid_amount')
            ->active()
            ->get();

        return view('reports.category-stats', compact('stats', 'fromDate', 'toDate', 'hasFilters'));
    }
}
