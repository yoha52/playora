<?php

namespace App\Livewire;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Court;
use App\Models\Ground;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public string $dateFilter = 'this_week';

    public function updatedDateFilter(): void
    {
        unset($this->bookingStats);
        unset($this->categoryBookingsData);
    }

    #[Computed]
    public function totalCategories(): int
    {
        return Category::query()->active()->count();
    }

    #[Computed]
    public function totalGrounds(): int
    {
        return Ground::query()->active()->count();
    }

    #[Computed]
    public function totalCourts(): int
    {
        return Court::query()->active()->count();
    }

    #[Computed]
    public function bookingStats(): array
    {
        [$startDate, $endDate] = $this->getDateRange();

        $query = Booking::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->notCancelled();

        return [
            'total_bookings' => (clone $query)->count(),
            'total_received' => (clone $query)->sum('paid_amount'),
            'total_due' => (clone $query)->selectRaw('SUM(total_amount - paid_amount) as due')->value('due') ?? 0,
        ];
    }

    #[Computed]
    public function categoryBookingsData(): Collection
    {
        [$startDate, $endDate] = $this->getDateRange();

        return Category::query()
            ->withCount(['bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->where('status', '!=', BookingStatus::Cancelled);
            }])
            ->orderByDesc('bookings_count')
            ->get(['id', 'name']);
    }

    #[Computed]
    public function upcomingBookings(): Collection
    {
        return Booking::query()
            ->with([
                'court:id,name,ground_id,category_id' => [
                    'ground:id,name',
                    'category:id,name',
                ],
                'user:id,name,contact_no',
            ])
            ->where(function ($query) {
                $query->where('date', '>', today())
                    ->orWhere(function ($q) {
                        $q->where('date', today())
                            ->where('start_time', '>', now()->format('H:i:s'));
                    });
            })
            ->notCancelled()
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();
    }

    public function getDateRange(): array
    {
        return match ($this->dateFilter) {
            'today' => [today(), today()],
            'yesterday' => [today()->subDay(), today()->subDay()],
            'this_week' => [today()->startOfWeek(), today()->endOfWeek()],
            'last_week' => [today()->subWeek()->startOfWeek(), today()->subWeek()->endOfWeek()],
            'this_month' => [today()->startOfMonth(), today()->endOfMonth()],
            'last_month' => [today()->subMonth()->startOfMonth(), today()->subMonth()->endOfMonth()],
            'this_year' => [today()->startOfYear(), today()->endOfYear()],
            default => [today(), today()],
        };
    }

    public function getFilterOptions(): array
    {
        return [
            'today' => __('general.today'),
            'yesterday' => __('general.yesterday'),
            'this_week' => __('general.this_week'),
            'last_week' => __('general.last_week'),
            'this_month' => __('general.this_month'),
            'last_month' => __('general.last_month'),
            'this_year' => __('general.this_year'),
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboard', [
            'filterOptions' => $this->getFilterOptions(),
        ]);
    }
}
