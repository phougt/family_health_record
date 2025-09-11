<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordLink extends Model
{
    protected $fillable = ['record_id', 'link'];
    protected $hidden = ['created_at', 'updated_at'];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
