<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'credit_id',
        'amount',
        'status',
        'payment_method',
        'description',
    ];

    public function credit(){
        return $this->belongsTo(Credits::class,'credit_id');
    }

    public function balance(){
        
    }
}
