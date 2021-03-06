<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class)->with('measurement:id,abbreviation,full_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }
}
