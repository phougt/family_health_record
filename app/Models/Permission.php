<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['role_id', 'name'];

    public function role()
    {
        return $this->belongsTo(GroupRole::class, 'role_id');
    }
}
