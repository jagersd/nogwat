<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class)->with('measurement');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }
}
