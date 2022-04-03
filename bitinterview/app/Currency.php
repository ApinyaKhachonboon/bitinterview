<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type'
    ];

    public function wallets()
    {
        return $this->hasMany('App\Wallet');
    }
    public function market_posts()
    {
        return $this->hasMany('App\MarketPost');
    }
    public function trades()
    {
        return $this->hasMany('App\Trade');
    }
}
