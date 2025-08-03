<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
