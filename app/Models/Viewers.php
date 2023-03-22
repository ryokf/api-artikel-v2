<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Viewers extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the user that owns the viewers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the article that owns the viewers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }
}
