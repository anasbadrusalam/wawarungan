<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Guarded(['id'])]
class Location extends Model
{
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
