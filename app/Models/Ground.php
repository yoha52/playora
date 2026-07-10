<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ground extends Model implements HasMedia
{
    use HasActiveScope, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'location',
        'parking_available',
        'camera_allowed',
        'waiting_area',
        'changing_room',
        'security_locker',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'parking_available' => 'boolean',
            'camera_allowed' => 'boolean',
            'waiting_area' => 'boolean',
            'changing_room' => 'boolean',
            'security_locker' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('picture')
            ->useDisk('media')
            ->useFallbackUrl(asset('assets/img/default.png'))
            ->useFallbackPath(public_path('assets/img/default.png'))
            ->singleFile();
    }

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Court::class);
    }

    #[Scope]
    protected function nearBy(Builder $query, float|string $lat, float|string $lng, float|int $radiusKm = 2): void
    {
        $query->selectRaw("(
                6371 * ACOS(
                    COS(radians({$lat})) * COS(radians(latitude)) *
                    COS(radians(longitude) - radians({$lng})) +
                    SIN(radians({$lat})) * SIN(radians(latitude))
                )
            ) AS distance")
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }
}
