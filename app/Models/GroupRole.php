<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupRole extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'group_id'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }
}
