<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketPost extends Model
{
    protected $table = 'market_posts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'fiat_id',
        'currency_id',
        'action',
        'amount',
        'price',
        'status'
    ];

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function currencies()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }
    public function trades()
    {
        return $this->hasMany('App\Trade');
    }
}
