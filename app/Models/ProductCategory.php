<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'image',
        'name',
        'description',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }
}
