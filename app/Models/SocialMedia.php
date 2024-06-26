<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;
    protected $fillable  = [
        'facebook',
        'instgram',
        'youtupe',
        'linkedin',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
