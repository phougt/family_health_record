<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    protected $fillable = ['role_id', 'name'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function role()
    {
        return $this->belongsTo(GroupRole::class, 'role_id');
    }
}
