<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function recipeItems(): HasMany
    {
        return $this->hasMany(RecipeItem::class)->with('measurement:id,abbreviation,full_name');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function favorited(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
