<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBookingStatuses extends Command
{
    protected $signature = 'bookings:update-statuses';

    protected $description = 'Update confirmed bookings to completed status after their end time has passed';

    public function handle(): int
    {
        $now = Carbon::now();

        $bookings = Booking::query()
            ->where('status', BookingStatus::Confirmed)
            ->where(function ($query) use ($now) {
                $query->whereDate('date', '<', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->whereDate('date', $now->toDateString())
                            ->whereTime('end_time', '<=', $now->toTimeString());
                    });
            })
            ->get();

        $count = $bookings->count();

        if ($count === 0) {
            $this->info('No bookings to update.');

            return self::SUCCESS;
        }

        foreach ($bookings as $booking) {
            $booking->update(['status' => BookingStatus::Completed]);
        }

        $this->info("Updated {$count} booking(s) to completed status.");

        return self::SUCCESS;
    }
}
