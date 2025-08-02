<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['group_id', 'name', 'color'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tags');
    }

    public function records()
    {
        return $this->belongsToMany(Record::class, 'record_tags');
    }
}
