<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PostImage extends Model
{
    use HasFactory;

    protected $table = 'post_images';

    protected $fillable = [
        'sub_title',
        'file_name',
        'image',

    ];

    /**
     * Get the post that uses this image.
     */
    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }
}