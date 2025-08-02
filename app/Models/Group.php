<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['group_profile', 'name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }

    public function roles()
    {
        return $this->hasMany(GroupRole::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function inviteLinks()
    {
        return $this->hasMany(InviteLink::class);
    }
}
