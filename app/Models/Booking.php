<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'paid_amount',
        'total_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'paid_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'status' => BookingStatus::class,
        ];
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(BookingTransaction::class);
    }

    public function getDurationInHours(): int
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $start->diffInHours($end);
    }

    public function getBalanceDue(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function canReceivePayment(): bool
    {
        return ! $this->isFullyPaid() && $this->status !== BookingStatus::Cancelled;
    }

    public function canCancel(): bool
    {
        if ($this->status === BookingStatus::Cancelled || $this->status === BookingStatus::Completed) {
            return false;
        }

        $bookingDateTime = Carbon::parse($this->date->format('Y-m-d').' '.$this->start_time);

        return $bookingDateTime->isFuture();
    }

    public function getFormattedStartTime(): string
    {
        return formatTime($this->start_time);
    }

    public function getFormattedEndTime(): string
    {
        return formatTime($this->end_time);
    }

    public function getFormattedTimeSlot(): string
    {
        return $this->getFormattedStartTime().' - '.$this->getFormattedEndTime();
    }

    #[Scope]
    protected function forDate(Builder $query, $date): void
    {
        $query->where('date', $date);
    }

    #[Scope]
    protected function forCourt(Builder $query, $courtId): void
    {
        $query->where('court_id', $courtId);
    }

    #[Scope]
    protected function confirmed(Builder $query): void
    {
        $query->where('status', BookingStatus::Confirmed);
    }

    #[Scope]
    protected function completed(Builder $query): void
    {
        $query->where('status', BookingStatus::Completed);
    }

    #[Scope]
    protected function cancelled(Builder $query): void
    {
        $query->where('status', BookingStatus::Cancelled);
    }

    #[Scope]
    protected function notCancelled(Builder $query): void
    {
        $query->where('status', '!=', BookingStatus::Cancelled);
    }

    #[Scope]
    protected function withDue(Builder $query): void
    {
        $query->whereColumn('paid_amount', '<', 'total_amount')
            ->where('status', '!=', BookingStatus::Cancelled);
    }

    #[Scope]
    protected function overlapping(Builder $query, $startTime, $endTime): void
    {
        $query->where(function ($q) use ($startTime, $endTime) {
            $q->where(function ($inner) use ($startTime, $endTime) {
                $inner->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            });
        });
    }

    #[Scope]
    protected function pastBookings(Builder $query): void
    {
        $query->where(function ($q) {
            $q->where('date', '<', today())
                ->orWhere(function ($inner) {
                    $inner->where('date', today())
                        ->where('end_time', '<=', now()->format('H:i:s'));
                });
        });
    }
}
