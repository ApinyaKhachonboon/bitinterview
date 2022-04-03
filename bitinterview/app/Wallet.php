<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'currency_id',
        'amount'
    ];

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function currencies()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }
}
