<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name'];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
