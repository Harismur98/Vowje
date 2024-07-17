<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Twoin1Stamp extends Model
{
    protected $table = '2in1stamps';
    
    use HasFactory;
    protected $fillable = [
        'stamp_id',
        'second_stamp_id',
        'is_2in1stamp',
    ];

    public function stamp()
    {
        return $this->belongsTo(Stamp::class);
    }

}
