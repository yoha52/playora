<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Court extends Model implements HasMedia
{
    use HasActiveScope, InteractsWithMedia;

    protected $fillable = [
        'ground_id',
        'category_id',
        'name',
        'active',
        'opening_time',
        'closing_time',
        'rate_per_hour',
    ];

    protected function casts(): array
    {
        return [
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

    public function ground(): BelongsTo
    {
        return $this->belongsTo(Ground::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
