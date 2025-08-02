<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name'];

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
