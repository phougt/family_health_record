<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;
    protected $fillable = ['group_profile', 'name', 'description', 'is_archived'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    protected $casts = [
        'is_archived' => 'boolean',
    ];

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

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function hospitals()
    {
        return $this->hasMany(Hospital::class);
    }
}
