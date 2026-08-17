<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    protected $fillable = [
        'title',
        'credit_by',
        'type_of_content',
        'articles',
        'post_images_id',
    ];

    /**
     * Get the image associated with this post.
     */
    public function postImage(): BelongsTo
    {
        return $this->belongsTo(PostImage::class, 'post_images_id');
    }
}