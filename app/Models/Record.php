<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'group_id',
        'records_type_id',
        'hospital_id',
        'doctor_id',
        'name',
        'note',
        'visit_date',
        'next_visit_date',
        'document'
    ];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function recordType()
    {
        return $this->belongsTo(RecordType::class, 'records_type_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'record_tags');
    }

    public function links()
    {
        return $this->hasMany(RecordLink::class);
    }
}
