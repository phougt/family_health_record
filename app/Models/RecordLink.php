<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordLink extends Model
{
    use SoftDeletes;
    protected $fillable = ['record_id', 'link'];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
