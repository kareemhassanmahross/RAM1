<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'product_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function images()
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }
}