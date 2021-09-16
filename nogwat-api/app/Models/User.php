<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation functions from here
     *
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'user_id_created');
    }

    public function activeLists()
    {
        return $this->hasMany(ActiveList::class, 'user_id_added');
    }

    public function groupInvites()
    {
        return $this->hasMany(GroupInvite::class,'invited_user_id');
    }
}
