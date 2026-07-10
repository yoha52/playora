<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'court_id' => ['required', 'exists:courts,id'],
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', Rule::enum(BookingStatus::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'paid_amount' => $this->paid_amount ?? 0,
            'total_amount' => $this->total_amount ?? 0,
            'status' => $this->status ?? BookingStatus::Confirmed->value,
        ]);
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if ($this->hasOverlappingBooking()) {
                    $validator->errors()->add(
                        'start_time',
                        __('general.booking_overlap_error')
                    );
                }

                if (! $this->isWithinCourtOperatingHours()) {
                    $validator->errors()->add(
                        'start_time',
                        __('general.booking_outside_hours_error')
                    );
                }
            },
        ];
    }

    protected function hasOverlappingBooking(): bool
    {
        $query = Booking::query()
            ->forCourt($this->court_id)
            ->forDate($this->date)
            ->overlapping($this->start_time, $this->end_time)
            ->notCancelled();

        if ($this->route('booking')) {
            $query->where('id', '!=', $this->route('booking')->id);
        }

        return $query->exists();
    }

    protected function isWithinCourtOperatingHours(): bool
    {
        $court = Court::find($this->court_id);

        if (! $court) {
            return false;
        }

        $openingTime = Carbon::parse($court->opening_time)->format('H:i');
        $closingTime = Carbon::parse($court->closing_time)->format('H:i');
        $startTime = $this->start_time;
        $endTime = $this->end_time;

        return $startTime >= $openingTime && $endTime <= $closingTime;
    }
}
