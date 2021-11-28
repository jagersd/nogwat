<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Group extends Model
{
    use HasFactory;

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'pivot'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }

    public function groupConfig()
    {
        return $this->hasOne(GroupConfig::class);
    }

    public function activeLists()
    {
        return $this->hasMany(ActiveList::class, 'group_id')
        ->where('date_purchased','>=',Carbon::now()->subDays(1)->toDateTimeString())
        ->OrWhere('date_purchased', null)
        ->with('store:id,name')
        ->with('measurement')
        ->with('addedUser')
        ->orderBy('store_id', 'DESC');
    }

    public function openInvites()
    {
        return $this->hasMany(GroupInvite::class, 'group_id')
        ->where('status','pending')
        ->with('invitees');
    }

    public function stores(){
        return $this->hasMany(Store::class, 'group_id');
    }


}
