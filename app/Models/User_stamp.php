<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User_stamp extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'stamp_id',
        'collected_stamp',
        'is_used',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stamp()
    {
        return $this->belongsTo(stamp::class);
    }
}
