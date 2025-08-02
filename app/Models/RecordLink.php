<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordLink extends Model
{
    protected $fillable = ['record_id', 'link'];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
