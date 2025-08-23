<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'slug', 'description', 'kind'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
