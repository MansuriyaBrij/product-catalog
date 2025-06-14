<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
        'name',
        'description',
        'price',
        'image_path',
        'is_active'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    // Optional shortcut for accessing category from a product
    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            Subcategory::class,
            'id',
            'id',
            'subcategory_id',
            'category_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
