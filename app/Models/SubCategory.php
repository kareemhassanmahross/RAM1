<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'desctription',
        'image',
        'category_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function catagory()
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }
}