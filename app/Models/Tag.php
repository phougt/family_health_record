<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
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
