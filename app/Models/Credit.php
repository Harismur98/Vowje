<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;
    protected $fillable = [
        'credit',
        'shop_id',
    ];

    public function shop(){
        return $this->belongsTo(Shops::class,'shop_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'credit_id');
    }
    
}
