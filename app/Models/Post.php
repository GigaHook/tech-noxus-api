<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @uses HasImages
 */
class Post extends Model
{
    use HasFactory, HasImages;

    protected $fillable = [
        'title',
        'text',
        'image',
    ];
}