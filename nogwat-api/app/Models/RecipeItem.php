<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class,'measurement_type_id');
    }
}
