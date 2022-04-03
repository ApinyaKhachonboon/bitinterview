<?php

namespace App\Http\Controllers;

use App\MarketPost;
use App\Currency;
use App\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($action = "buy", $fiat = "THB", $crypto = "BTC")
    {
        /// เช็คว่ามี fiat จริงรึป่าว ///
        $check_fiat = Currency::where('type', 'national')->where('name', $fiat)->count();
        /// เช็คว่ามี crypto จริงรึป่าว ///
        $check_crypto = Currency::where('type', 'crypto')->where('name', $crypto)->count();
        if($check_fiat == 0 || $check_crypto == 0)
        {
            return redirect('/')->with('not_ok', 'true')->with('msg', 'มีข้อผิดพลาด!!');
        }
        
        $fiat_id = Currency::where('type', 'national')
                            ->where('name', $fiat)->first()->id;

        $currency = Currency::where('name', $crypto)->first();
        $market_posts = MarketPost::where('action', $action)
                                    ->where('currency_id', $currency->id)
                                    ->where('fiat_id', $fiat_id)
                                    ->where('status', 'open')
                                    ->orderby('price', 'ASC')
                                    ->paginate(10);
        
        $national = Currency::where('type', 'national')->get();
        $list_national = [];
        foreach ($national as $value)
        {
            $list_national[$value->id] = $value->name;
        }

        $wallet = Wallet::where('user_id', Auth::id())->where('currency_id', $currency->id)->first();
        $balance_cypto = 0;
        if($wallet != null)
        {
            $balance_cypto = $wallet->amount;
        }
        $wallet_fiat = Wallet::where('user_id', Auth::id())->where('currency_id', $fiat_id)->first();
        $balance_fiat = 0;
        if($wallet_fiat != null)
        {
            $balance_fiat = $wallet_fiat->amount;
        }

        
        return view('index', compact(
            'action',
            'market_posts',
            'fiat',
            'crypto',
            'list_national',
            'fiat_id',
            'balance_cypto',
            'balance_fiat'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
