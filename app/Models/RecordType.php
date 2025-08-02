<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordType extends Model
{
    protected $fillable = ['name'];

    public function records()
    {
        return $this->hasMany(Record::class, 'records_type_id');
    }
}
