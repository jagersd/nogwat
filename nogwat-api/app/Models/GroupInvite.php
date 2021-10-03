<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupInvite extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relation functions from here
     *
     */
    public function group()
    {
        return $this->belongsTo(Group::class,'group_id');
    }

    public function invitor()
    {
        return $this->belongsTo(User::class,'invitor_user_id');
    }

    public function invitees()
    {
        return $this->belongsTo(User::class,'invited_user_id');
    }

}
