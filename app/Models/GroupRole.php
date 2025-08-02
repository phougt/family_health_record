<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    protected $fillable = ['name', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }
}
