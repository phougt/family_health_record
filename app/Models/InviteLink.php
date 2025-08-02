<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteLink extends Model
{
    protected $fillable = ['link', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
