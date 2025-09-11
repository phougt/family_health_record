<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InviteLink extends Model
{
    protected $fillable = ['link', 'group_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
