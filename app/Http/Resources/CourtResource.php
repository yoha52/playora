<?php

namespace App\Http\Resources;

use App\Models\Court;
use App\Services\GeneralService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Court */
class CourtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->getFirstMediaUrl('picture'),
            'category_id' => $this->category_id,
            'category_name' => $this->whenLoaded('category', fn () => $this->category->name),
            'category_image' => $this->whenLoaded('category', fn () => $this->category->getFirstMediaUrl('picture')),
            'rate_per_hour' => $this->rate_per_hour,
            'opening_time' => formatTime($this->opening_time),
            'closing_time' => formatTime($this->closing_time),
            'currency_symbol' => GeneralService::getCurrencySymbol(SettingsService::getCurrency()),
        ];
    }
}
