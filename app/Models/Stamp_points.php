<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Stamp_points extends Model
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'stamp_id',
        'user_id',

    ];

    public function stamp()
    {
        return $this->belongsTo(Stamp::class,'stamp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
