<?php

namespace App\Models;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
