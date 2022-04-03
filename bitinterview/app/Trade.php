<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = 'trades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'market_post_id',
        'user_id',
        'currency_id',
        'action',
        'outer_name',
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
    public function market_posts()
    {
        return $this->belongsTo('App\MarketPost', 'market_post_id');
    }
}
