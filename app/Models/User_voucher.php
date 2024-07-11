<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User_voucher extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'voucher_id',
        'is_used',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function voucher()
    {
        return $this->belongsTo(Voucher::class,'voucher_id'); // Assuming the model name is Voucher
    }
}
