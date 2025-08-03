<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    protected $fillable = ['role_id', 'name'];

    public function role()
    {
        return $this->belongsTo(GroupRole::class, 'role_id');
    }
}
