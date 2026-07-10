<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Services\GeneralService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Booking */
class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'court' => [
                'id' => $this->court?->id,
                'name' => $this->court?->name,
                'ground' => $this->court?->ground?->name,
                'ground_address' => $this->court?->ground?->address,
                'ground_latitude' => (float) $this->court?->ground?->latitude,
                'ground_longitude' => (float) $this->court?->ground?->longitude,
                'ground_image' => $this->court?->ground?->getFirstMediaUrl('picture'),
                'category' => $this->court?->category?->name,
                'rate_per_hour' => (float) $this->court?->rate_per_hour,
            ],
            'date' => formatDate($this->date),
            'start_time' => $this->getFormattedStartTime(),
            'end_time' => $this->getFormattedEndTime(),
            'duration_hours' => $this->getDurationInHours(),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'contact_no' => $this->user->contact_no,
            ]),
            'paid_amount' => (float) $this->paid_amount,
            'total_amount' => (float) $this->total_amount,
            'balance_due' => (float) $this->getBalanceDue(),
            'status' => $this->status->label(),
            'notes' => $this->notes ?? '',
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'currency_symbol' => GeneralService::getCurrencySymbol(SettingsService::getCurrency()),
        ];
    }
}
