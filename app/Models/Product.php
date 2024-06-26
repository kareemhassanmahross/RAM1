<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'amount',
        'sub_category_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function subcatagory()
    {
        return $this->belongsTo(SubCategory::class, "sub_category_id", "id");
    }
    public function images()
    {
        return $this->hasMany(image::class);
    }
}