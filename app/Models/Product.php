<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'code', 'slug', 'name', 'brand', 'subcategory',
        'short_description', 'description', 'price', 'currency', 'presentation',
        'net_content', 'units', 'ingredients', 'usage', 'precautions',
        'catalog_benefits', 'image', 'images', 'source_page', 'availability', 'featured',
    ];

    protected function casts(): array
    {
        return ['catalog_benefits' => 'array', 'images' => 'array', 'featured' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $query, string $term) => $query->where(
            fn (Builder $query) => $query
                ->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('brand', 'like', "%{$term}%")
                ->orWhereHas('category', fn (Builder $query) => $query->where('name', 'like', "%{$term}%"))
        ));
    }
}
