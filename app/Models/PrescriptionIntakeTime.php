<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionIntakeTime extends Model
{
    protected $table = 'prescription_intake_time';

    protected $fillable = ['time', 'note'];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'prescription_intake_time_id');
    }
}
