<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordPrescription extends Model
{
    protected $fillable = ['record_id', 'prescription_id'];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
