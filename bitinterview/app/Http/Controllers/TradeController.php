<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Trade;
use App\MarketPost;
use App\Wallet;
use App\Currency;
use App\User;

class TradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id, $fiat_name, $crypto_name)
    {
        /// เช็คว่ามี fiat จริงรึป่าว ///
        $check_fiat = Currency::where('type', 'national')->where('name', $fiat_name)->count();
        /// เช็คว่ามี crypto จริงรึป่าว ///
        $check_crypto = Currency::where('type', 'crypto')->where('name', $crypto_name)->count();
        if($check_fiat == 0 || $check_crypto == 0)
        {
            return redirect('/');
        }

        $market_post = MarketPost::where('id', $id)->where('status', 'open')->first();
        if($market_post == null)
        {
            return redirect('/')->with('msg', 'ไม่มีร้านนี้อยู่!!');
        }
        if($market_post->action == 'buy')
        {
            $wallet = Wallet::where('user_id', Auth::id())
            ->where('currency_id', $market_post->currency_id)
            ->first();
        }
        elseif($market_post->action == 'sell')
        {
            $wallet = Wallet::where('user_id', Auth::id())
            ->where('currency_id', $market_post->fiat_id)
            ->first();
        }

        $balance = 0;
        if($wallet != null)
        {
            $balance = $wallet->amount;
        }

        return view('trade.index', compact(
            'market_post',
            'wallet',
            'fiat_name',
            'crypto_name',
            'id',
            'balance'
        ));
    }

    public function trade(Request $request, $id)
    {

        // echo dd($request);
        /// id คือ market_id ///
        // เช็คว่ามีร้านจริงมั้ย ////
        $check_market = MarketPost::where('id', $id)->where('status', 'open')->count();
        if($check_market == 0)
        {
            return redirect('/')->with('not_ok', 'true')->with('msg', 'ไม่มีร้านนี้อยู่!!');
        }
        $market_post = MarketPost::where('id', $id)->where('status', 'open')->first();
        
        /// กรณีขายให้กับร้านค้า //
        if($market_post->action == "buy")
        {
            $wellet = Wallet::where('user_id', Auth::id())->where('currency_id', $market_post->currency_id)->first();
            $market_wallet = Wallet::where('user_id', $market_post->user_id)->where('currency_id', $market_post->currency_id)->first();
            /// กรณีราคาที่จะขายมากกว่าที่รับซื้อ  หรือ กรณีราคาขายน้อยกว่าค่า min  หรือ เงินที่มีไม่พอที่จะขาย ///
            if($request->amount > $market_post->amount || 
            $request->amount * $market_post->price < $market_post->min ||
            $request->amount > $wellet->amount ||
            floor($request->amount * $market_post->price) > $market_post->max)
            {
                return redirect('/')->with('not_ok', 'true')->with('msg', 'ผิดพลาด!!');
            }

            /// กรณีร้านค้ายังไม่มีกระเป๋าตัง //
            if($market_wallet == null)
            {
                $market_wallet = new Wallet();
                $market_wallet->user_id = $market_post->user_id;
                $market_wallet->currency_id = $market_post->currency_id;
                $market_wallet->amount = 0;
            }

            // update เงินใน wallet และ market_post
            // ลดจำนวนเงินใน wallet ของ user ///
            $wellet->amount -= $request->amount;
            /// เพิ่มจำนวนเงินใน wallet ของ user ร้านค้า ///
            $market_wallet->amount += $request->amount;
            /// ลดจำนวนเงินใน market_post ที่ตั้งรับ ///
            $market_post->amount -= $request->amount;
            // กรณี amount ใน market เหลือ 0 หรือ น้อยกว่าค่า min ให้ close //
            if($market_post->amount <= 0 || 
            $market_post->amount * $market_post->price < $market_post->min)
            {
                $market_post->status = 'close';
            }
            // ปรับค่า max ใหม่ ////
            else if($market_post->max > $market_post->amount * $market_post->price)
            {
                $market_post->max = $market_post->amount * $market_post->price;
            }

            /// สร้างหลักฐานการ trade ทั้ง 2 ฝั่ง ///

            // ฝั่ง user ////
            $trade_user = new Trade();
            $trade_user->market_post_id = $id;
            $trade_user->user_id = Auth::id();
            $trade_user->currency_id = $market_post->currency_id;
            $trade_user->action = 'sell';
            $trade_user->amount = $request->amount;

            $trade_user->save();

            // ฝั่ง market ////
            $trade_market = new Trade();
            $trade_market->market_post_id = $id;
            $trade_market->user_id = $market_post->user_id;
            $trade_market->currency_id = $market_post->currency_id;
            $trade_market->action = 'buy';
            $trade_market->amount = $request->amount;

            $trade_market->save();

            $wellet->save();
            $market_post->save();
            $market_wallet->save();
        }
        // กรณีซื้อจากร้านค้า ///
        elseif($market_post->action == "sell"){
            $wellet = Wallet::where('user_id', Auth::id())->where('currency_id', $market_post->fiat_id)->first();
            $check_market_wallet = Wallet::where('user_id', $market_post->user_id)->where('currency_id', $market_post->fiat_id)->count();
            if($check_market_wallet == 0)
            {
                $market_wallet = new Wallet();
                $market_wallet->user_id = $market_post->user_id;
                $market_wallet->currency_id = $market_post->fiat_id;
                $market_wallet->amount = 0;
            }
            else {
                $market_wallet = Wallet::where('user_id', $market_post->user_id)->where('currency_id', $market_post->fiat_id)->first();
            }
            
            /// กรณีราคาที่จะขายมากกว่าที่ตั้งขาย  หรือ กรณีราคาซื้อน้อยกว่าค่า min  หรือ เงินที่มีไม่พอที่จะซื้อ ///
            if($request->amount > $market_post->amount || 
            $request->amount * $market_post->price < $market_post->min ||
            $request->amount * $market_post->price > $wellet->amount ||
            $request->amount * $market_post->price > $market_post->max)
            {
                return redirect('/')->with('not_ok', 'true')->with('msg', 'ผิดพลาด!!');
            }

            /// กรณีร้านค้ายังไม่มีกระเป๋าตัง //
            if($market_wallet == null)
            {
                $market_wallet = new Wallet();
                $market_wallet->user_id = $market_post->user_id;
                $market_wallet->currency_id = $market_post->currency_id;
                $market_wallet->amount = 0;
            }

            /// update เงินใน wallet และ market_post
            /// ลดจำนวนเงินใน wallet ของ user ///
            $wellet->amount -= $request->amount * $market_post->price;
            /// เพิ่มจำนวนเงินใน wallet ของ user ร้านค้า ///
            $market_wallet->amount += $request->amount * $market_post->price;
            /// ลดจำนวนเงินใน market_post ที่ตั้งขาย ///
            $market_post->amount -= $request->amount;
            // กรณี amount ใน market เหลือ 0 หรือ น้อยกว่าค่า min ให้ close //
            if($market_post->amount <= 0 || 
            $market_post->amount * $market_post->price < $market_post->min)
            {
                $market_post->status = 'close';
            }
            // ปรับค่า max ใหม่ ////
            else if($market_post->max > $market_post->amount * $market_post->price)
            {
                $market_post->max = $market_post->amount * $market_post->price;
            }

            /// สร้างหลักฐานการ trade ทั้ง 2 ฝั่ง ///

            // ฝั่ง user ////
            $trade_user = new Trade();
            $trade_user->market_post_id = $id;
            $trade_user->user_id = Auth::id();
            $trade_user->currency_id = $market_post->currency_id;
            $trade_user->action = 'buy';
            $trade_user->amount = $request->amount;

            $trade_user->save();

            // ฝั่ง market ////
            $trade_market = new Trade();
            $trade_market->market_post_id = $id;
            $trade_market->user_id = $market_post->user_id;
            $trade_market->currency_id = $market_post->currency_id;
            $trade_market->action = 'sell';
            $trade_market->amount = $request->amount;

            $trade_market->save();

            $wellet->save();
            $market_post->save();
            $market_wallet->save();
        }
        

        return redirect('/')->with('ok', 'true')->with('msg', 'Trade succes!!');
    }
}
