<?php
namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupRole extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'group_id', 'type'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    protected $casts = [
        'type' => RoleType::class,
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_role_permissions', 'role_id', 'permission_id');
    }
}
