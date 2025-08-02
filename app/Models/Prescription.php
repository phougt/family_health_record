<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['medicine_id', 'prescription_intake_time_id', 'note'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function intakeTime()
    {
        return $this->belongsTo(PrescriptionIntakeTime::class, 'prescription_intake_time_id');
    }

    public function recordPrescriptions()
    {
        return $this->hasMany(RecordPrescription::class);
    }
}
