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
        'total_used',
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
}
