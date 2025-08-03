<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordType extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'group_id'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function records()
    {
        return $this->hasMany(Record::class, 'records_type_id');
    }
}
