<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Voucher extends Model
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'discount',
        'total_used',
        'max_voucher_used',
        'shop_id',
        'min_spend',
        'expired_date',
        't&c',
        'is_active',
    ];

    public function shop()
    {
        return $this->belongsTo(Shops::class,'shop_id');
    }
}
