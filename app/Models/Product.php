<?php

namespace App\Models;

use App\Enums\ProductType;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

#[Guarded(['id'])]
#[ObservedBy(ProductObserver::class)]
class Product extends Model
{
    use HasTags, HasSlug, HasFactory, LogsActivity;

    protected static $recordEvents = ['updated'];

    protected function casts(): array
    {
        return [
            'manage_stock' => 'boolean',
            'type' => ProductType::class,
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite()
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'cost', 'price', 'sku', 'code', 'barcode'])
            ->logOnlyDirty()
            ->useLogName('product');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(config('activitylog.activity_model'), 'subject')->latest();
    }
}
