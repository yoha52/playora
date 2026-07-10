<?php

namespace App\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveScope
{
    public function scopeActive(Builder $query): void
    {
        $query->where($this->getTable() . '.active', true);
    }
}
