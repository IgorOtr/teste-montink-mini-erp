<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description'];

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}

