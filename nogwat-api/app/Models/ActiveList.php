<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class,'measurement_type_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function addedUser()
    {
        return $this->belongsTo(User::class,'user_id_added');
    }

    public function purchasedUser()
    {
        return $this->belongsTo(User::class,'user_id_purchased');
    }
}
