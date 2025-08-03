<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordType extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];

    public function records()
    {
        return $this->hasMany(Record::class, 'records_type_id');
    }
}
