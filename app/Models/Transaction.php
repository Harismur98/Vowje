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
        'transaction_type' // This indicates if the transaction is an addition or deduction
    ];

    const TRANSACTION_TYPE_ADD = 'add';
    const TRANSACTION_TYPE_DEDUCT = 'deduct';

    public function credit(){
        return $this->belongsTo(Credit::class,'credit_id');
    }

    //not used
    public function processTransaction(){
        $credit = $this->credit;
        
        if ($this->transaction_type == self::TRANSACTION_TYPE_ADD) {
            $credit->credit += $this->amount;
        } elseif ($this->transaction_type == self::TRANSACTION_TYPE_DEDUCT) {
            $credit->credit -= $this->amount;
        }

        $credit->save();
    }
}
