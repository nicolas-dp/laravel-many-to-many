<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug', 'cover_image', 'category_id'];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function tag(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    
}