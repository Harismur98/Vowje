<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Stamp extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'reward',
        'total_required_stamps',
        'max_stamp_used',
        'shop_id',
        'expired_date',
        't&c',
        'total_required_stamps',
    ];

    public function shop()
    {
        return $this->belongsTo(Shops::class,'shop_id');
    }
    public function user_stamps()
    {
        return $this->hasMany(User_stamp::class);
    }
}
