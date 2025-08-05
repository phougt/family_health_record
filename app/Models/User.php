<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BloodType;
use App\Models\Group;
use App\Models\Tag;
use App\Models\UserGroup;
use App\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'profile',
        'firstname',
        'lastname',
        'username',
        'password',
        'email',
        'blood_type_id',
        'hospital_id',
        'doctor_id',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'user_tags');
    }

    public function roles()
    {
        return $this->belongsToMany(GroupRole::class, 'user_groups', 'user_id', 'role_id');
    }

    public function getPermissions(int $groupId)
    {
        return $this->roles()
            ->where('group_roles.group_id', $groupId)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug');
    }
}
