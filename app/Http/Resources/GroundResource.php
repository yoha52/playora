<?php

namespace App\Http\Resources;

use App\Models\Ground;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Ground */
class GroundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image' => $this->getFirstMediaUrl('picture'),
        ];

        // Include description only when specifically requested
        if ($request->routeIs('api.grounds.show')) {
            $data['description'] = $this->description;
            $data['parking_available'] = $this->parking_available;
            $data['camera_allowed'] = $this->camera_allowed;
            $data['waiting_area'] = $this->waiting_area;
            $data['changing_room'] = $this->changing_room;
            $data['security_locker'] = $this->security_locker;
        }

        // Include courts when loaded
        if ($this->relationLoaded('courts')) {
            $data['courts'] = CourtResource::collection($this->courts);
        }

        return $data;
    }
}
