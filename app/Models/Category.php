<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get all of the article for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }

    /**
     * Get all of the userInterest for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userInterests(): HasMany
    {
        return $this->hasMany(UserInterest::class, 'category_id', 'id');
    }
}
