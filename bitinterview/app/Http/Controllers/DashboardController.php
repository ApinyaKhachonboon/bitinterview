<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\User;
use App\Wallet;
use App\Currency;
use App\MarketPost;
use App\Trade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    protected $btc_thb = 1531716.70;
    protected $eth_thb = 113572.62;
    protected $xrp_thb = 28.565;
    protected $doge_thb = 4.63;

    protected $btc_usd = 45951.501;
    protected $eth_usd = 3407.179;
    protected $xrp_usd = 0.857;
    protected $doge_usd = 0.139;

    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    ////// user ///////
    public function index()
    {
        return view('dashboard.home');
    }

    public function editUser()
    {
        return view('dashboard.edituser');
    }

    public function updateUser(Request $request)
    {
        $id = Auth::id();
        $user = User::find($id);
        $check_name = User::where('name', $request->name)->count();
        if($check_name > 0)
        {
            return redirect('dashboard/edituser')->with('not_ok', 'true')->with('msg', 'ชื่อซ้ำ!!');
        }
        $user->name = $request->name;
        $user->password = Hash::make($request->password);

        $user->save();
        return redirect('/dashboard');
    }

    //// create buy /////////
    public function createbuy($fiat_name = "THB", $crypto_name = "BTC")
    {
        /// เช็คว่ามี fiat จริงรึป่าว ///
        $check_fiat = Currency::where('type', 'national')->where('name', $fiat_name)->count();
        /// เช็คว่ามี crypto จริงรึป่าว ///
        $check_crypto = Currency::where('type', 'crypto')->where('name', $crypto_name)->count();
        if($check_fiat == 0 || $check_crypto == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'มีข้อผิดพลาด!!');
        }

        $crypto = Currency::where('type', 'crypto')->get();
        $national = Currency::where('type', 'national')->get();
        
        $fiat_id = Currency::where('name', $fiat_name)->first();
        $fiat_id = $fiat_id->id;

        $crypto_id = Currency::where('name', $crypto_name)->first();
        $crypto_id = $crypto_id->id;

        /// ดึงค่ายอดเงินของ user ////
        $user_id = Auth::id();

        $balance = 0;
        $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $fiat_id)->count();
        if($wallet > 0)
        {
            $balance = Wallet::where('user_id', $user_id)
                            ->where('currency_id', $fiat_id)
                            ->first()->amount;
        }
        $limit_max = $balance;
        $amount_max = $limit_max;
        $price = 0;

        if($fiat_name == 'THB')
        {
            if($crypto_name == 'BTC')
            {
                $amount_max /= $this->btc_thb;
                $price = $this->btc_thb;
            }
            elseif($crypto_name == 'ETH')
            {
                $amount_max /= $this->eth_thb;
                $price = $this->eth_thb;
            }
            elseif($crypto_name == 'XRP')
            {
                $amount_max /= $this->xrp_thb;
                $price = $this->xrp_thb;
            }
            elseif($crypto_name == 'DOGE')
            {
                $amount_max /= $this->doge_thb;
                $price = $this->doge_thb;
            }
        }
        elseif($fiat_name == 'USD')
        {
            if($crypto_name == 'BTC')
            {
                $amount_max /= $this->btc_usd;
                $price = $this->btc_usd;
            }
            elseif($crypto_name == 'ETH')
            {
                $amount_max /= $this->eth_usd;
                $price = $this->eth_usd;
            }
            elseif($crypto_name == 'XRP')
            {
                $amount_max /= $this->xrp_usd;
                $price = $this->xrp_usd;
            }
            elseif($crypto_name == 'DOGE')
            {
                $amount_max /= $this->doge_usd;
                $price = $this->doge_usd;
            }
        }

        $amount_max = round($amount_max, 8);

        $balance = number_format($balance, 2);

        $list_crypto = [];
        $list_national = [];

        foreach ($crypto as $value)
        {
            $list_crypto[$value->id] = $value->name;
        }

        foreach ($national as $value)
        {
            $list_national[$value->id] = $value->name;
        }

        return view('dashboard.createbuy', compact(
            'list_crypto', 
            'list_national', 
            'fiat_name', 
            'crypto_name',
            'fiat_id',
            'crypto_id',
            'balance',
            'limit_max',
            'amount_max',
            'price'
        ));
    }

    public function insertbuy(Request $request)
    {
        // echo dd($request);

        /// เช็คค่า fiat ว่าถูกต้องมั้ย ////
        $check_fiat = Currency::where('type', 'national')->where('id', $request->fiat_id)->count();
        if($check_fiat == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ไม่มีค่า fiat นี้!!');
        }

        /// เช็คค่า crypto ว่าถูกต้องมั้ย ////
        $check_crypto = Currency::where('type', 'crypto')->where('id', $request->currency_id)->count();
        if($check_crypto == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ไม่มีค่า crypto นี้!!');
        }

        /// ดึงกระเป๋าตังมาดู ////
        $wallet_count = Wallet::where('user_id', Auth::id())->where('currency_id', $request->fiat_id)->count();
        $balance = 0;
        $wallet = [];
        if($wallet_count > 0)
        {
            $wallet = Wallet::where('user_id', Auth::id())->where('currency_id', $request->fiat_id)->first();
            $balance = $wallet->amount;
        }

        /// กรณีงบไม่พอ ////
        if($balance <= 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'คุณไม่มีเงินสกุลนี้เหลือแล้ว!!');
        }

        if(round($request->price * $request->amount) > $balance)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'กรุณาฝากเงินเพิ่ม!!');
        }

        //// กรณีตั้ง Amount น้อยกว่า min ///
        if($request->min / $request->price > $request->amount || $request->amount <= 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ค่า Amount น้อยเกินไป!!');
        }

        /// กรณี limit ผิด THB////
        if($request->fiat_name == "THB")
        {
            if($request->min < 50 || $request->min > $request->max || $request->max > $balance)
            {
                return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ค่า limit ผิดพลาด!!');
            }
        }
        /// กรณี limit min ผิด USD////
        else if($request->fiat_name == "USD")
        {
            if($request->min < 2 || $request->min > $request->max || $request->max > $balance)
            {
                return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ค่า limit ผิดพลาด!!');
            }
        }

        $market_post = new MarketPost();
        $market_post->user_id = Auth::id();
        $market_post->currency_id = $request->currency_id;
        $market_post->fiat_id = $request->fiat_id;
        $market_post->action = 'buy';
        $market_post->amount = $request->amount;
        $market_post->price = $request->price;
        $market_post->min = $request->min;
        $market_post->max = $request->max;

        $market_post->save();

        //// ตัดเงินตามจำนวนที่ตั้งรับ ///
        $wallet->amount -= round($request->amount * $request->price);
        $wallet->save();

        return redirect('dashboard')->with('ok', 'true')->with('msg', 'create buy success!!');
    }

    //// create sell /////////
    public function createsell($fiat_name = "THB", $crypto_name = "BTC")
    {
        /// เช็คว่ามี fiat จริงรึป่าว ///
        $check_fiat = Currency::where('type', 'national')->where('name', $fiat_name)->count();
        /// เช็คว่ามี crypto จริงรึป่าว ///
        $check_crypto = Currency::where('type', 'crypto')->where('name', $crypto_name)->count();
        if($check_fiat == 0 || $check_crypto == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'มีข้อผิดพลาด!!');
        }

        $crypto = Currency::where('type', 'crypto')->get();
        $national = Currency::where('type', 'national')->get();
        
        $fiat_id = Currency::where('name', $fiat_name)->first();
        $fiat_id = $fiat_id->id;

        $crypto_id = Currency::where('name', $crypto_name)->first();
        $crypto_id = $crypto_id->id;

        /// ดึงค่ายอดเงินของ user ////
        $user_id = Auth::id();

        $balance = 0;
        $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $crypto_id)->count();
        if($wallet > 0)
        {
            $balance = Wallet::where('user_id', $user_id)
                            ->where('currency_id', $crypto_id)
                            ->first()->amount;
        }
        $limit_max = $balance;
        $amount_max = $limit_max;
        $price = 0;

        if($fiat_name == 'THB')
        {
            if($crypto_name == 'BTC')
            {
                $limit_max *= $this->btc_thb;
                $price = $this->btc_thb;
            }
            elseif($crypto_name == 'ETH')
            {
                $limit_max *= $this->eth_thb;
                $price = $this->eth_thb;
            }
            elseif($crypto_name == 'XRP')
            {
                $limit_max *= $this->xrp_thb;
                $price = $this->xrp_thb;
            }
            elseif($crypto_name == 'DOGE')
            {
                $limit_max *= $this->doge_thb;
                $price = $this->doge_thb;
            }
        }
        elseif($fiat_name == 'USD')
        {
            if($crypto_name == 'BTC')
            {
                $limit_max *= $this->btc_usd;
                $price = $this->btc_usd;
            }
            elseif($crypto_name == 'ETH')
            {
                $limit_max *= $this->eth_usd;
                $price = $this->eth_usd;
            }
            elseif($crypto_name == 'XRP')
            {
                $limit_max *= $this->xrp_usd;
                $price = $this->xrp_usd;
            }
            elseif($crypto_name == 'DOGE')
            {
                $limit_max *= $this->doge_usd;
                $price = $this->doge_usd;
            }
        }

        $amount_max = round($amount_max, 8);
        $limit_max = floor($limit_max);
        $balance = round($balance, 8);

        $list_crypto = [];
        $list_national = [];

        foreach ($crypto as $value)
        {
            $list_crypto[$value->id] = $value->name;
        }

        foreach ($national as $value)
        {
            $list_national[$value->id] = $value->name;
        }

        return view('dashboard.createsell', compact(
            'list_crypto', 
            'list_national', 
            'fiat_name', 
            'crypto_name',
            'fiat_id',
            'crypto_id',
            'balance',
            'limit_max',
            'amount_max',
            'price'
        ));
    }

    public function insertsell(Request $request)
    {
        // echo dd($request);

        /// เช็คค่า fiat ว่าถูกต้องมั้ย ////
        $check_fiat = Currency::where('type', 'national')->where('id', $request->fiat_id)->count();
        if($check_fiat == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ไม่มีค่า fiat นี้!!');
        }

        /// เช็คค่า crypto ว่าถูกต้องมั้ย ////
        $check_crypto = Currency::where('type', 'crypto')->where('id', $request->currency_id)->count();
        if($check_crypto == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ไม่มีค่า crypto นี้!!');
        }

        /// ดึงกระเป๋าตังมาดู ////
        $wallet_count = Wallet::where('user_id', Auth::id())->where('currency_id', $request->currency_id)->count();
        $balance = 0;
        $wallet = [];
        if($wallet_count > 0)
        {
            $wallet = Wallet::where('user_id', Auth::id())->where('currency_id', $request->currency_id)->first();
            $balance = $wallet->amount;
        }

        /// กรณีงบไม่พอ ////
        if($balance < $request->amount)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'กรุณาฝากเงินเพิ่ม!!');
        }

        //// กรณีตั้ง Amount น้อยกว่า min ///
        if($request->amount * $request->price < $request->min || $request->amount <= 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ค่า Amount น้อยเกินไป!!');
        }

        /// กรณี limit ผิด THB////
        if($request->fiat_name == "THB")
        {
            if($request->min < 50 || $request->min > $request->max || $request->max > $balance * $request->price)
            {
                return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ค่า limit ผิดพลาด!!');
            }
        }
        /// กรณี limit min ผิด USD////
        else if($request->fiat_name == "USD")
        {
            if($request->min < 2 || $request->min > $request->max || $request->max > $balance * $request->price)
            {
                return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ค่า limit ผิดพลาด!!');
            }
        }

        $market_post = new MarketPost();
        $market_post->user_id = Auth::id();
        $market_post->currency_id = $request->currency_id;
        $market_post->fiat_id = $request->fiat_id;
        $market_post->action = 'sell';
        $market_post->amount = $request->amount;
        $market_post->price = $request->price;
        $market_post->min = $request->min;
        $market_post->max = $request->max;

        $market_post->save();

        //// ตัดเงินตามจำนวนที่ตั้งรับ ///
        $wallet->amount -= $request->amount;
        $wallet->save();

        return redirect('dashboard')->with('ok', 'true')->with('msg', 'create sell success!!');
    }

    //// deposit /////
    public function showdeposit($fiat = "THB")
    {
        ///// เช็คว่ามีสกุล national นี้จริงมั้ย /////
        $check_fiat = Currency::where('type', 'national')->where('name', $fiat)->count();
        if($check_fiat == 0)
        {
            return view('dashboard.dashboard');
        }

        $currency_id = Currency::where('name', $fiat)->first();
        $currency_id = $currency_id->id;

        $user_id = Auth::id();
        $wallets = Wallet::where('user_id', $user_id)->get();
        $balance = 0;
        foreach($wallets as $value)
        {
            /// กรณีมีค่าเงินนี้อยู่แล้วจะดึงจำนวนเงินมาโชว์
            if($value->currencies->name == $fiat)
            {
                $balance = $value->amount;
            }
        }

        //// แปลงค่าตัวเลขก่อนโชว์ ////
        $balance = number_format($balance, 2);
        $national = Currency::where('type', 'national')->get();
        $list_national = [];
        foreach ($national as $value)
        {
            $list_national[$value->id] = $value->name;
        }
        return view('dashboard.deposit', compact('list_national', 'balance', 'currency_id', 'fiat'));
    }

    function deposit(Request $request)
    {
        // echo dd($request);
        $user_id = Auth::id();
        $check_fiat = Wallet::where('user_id', $user_id)
                            ->where('currency_id', $request->currency_id)
                            ->count();

        /// กรณีไม่มี fiat ดังกล่าวให้ create ใหม่ 
        if($check_fiat == 0)
        {
            $wallet = new Wallet();
            $wallet->user_id = $user_id;
            $wallet->currency_id = $request->currency_id;
            $wallet->amount = $request->amount;
            $wallet->save();

            return redirect('dashboard')->with('ok', 'true')->with('msg', 'ฝากเงินเรียบร้อย!!');
        }
        /// กรณีมี fiat อยู่แล้วให้ update 
        else
        {
            $wallet = Wallet::where('currency_id', $request->currency_id)
                            ->where('user_id', $user_id)->first();
            $wallet->amount += $request->amount;
            $wallet->save();
            return redirect('dashboard')->with('ok', 'true')->with('msg', 'ฝากเงินเรียบร้อย!!');
        }
    }

    ////// transfer ///////
    public function showtransfer($crypto_name = 'BTC')
    {
        $currencies = Currency::where('type', 'crypto')->get();
        $currency_id = Currency::where('name', $crypto_name)->first()->id;
        $balance = 0;
        $wallet = Wallet::where('user_id', Auth::id())->where('currency_id', $currency_id)->first();
        if($wallet != null)
        {
            $balance = $wallet->amount;
        }
        $crypto_list = [];
        foreach ($currencies as $value)
        {
            $crypto_list[$value->id] = $value->name;
        }
        return view('dashboard.transfer', compact(
            'crypto_list', 
            'balance',
            'currency_id',
            'crypto_name'
        ));
    }

    public function transfer(Request $request)
    {
        /// เช็คว่ามี crypto จริงรึป่าว ///
        $check_crypto = Currency::where('type', 'crypto')->where('name', $request->crypto_name)->count();
        if($check_crypto == 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ไม่มีสกุลดังกล่าว!!');
        }
        if($request->amount <= 0)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'จำนวนที่จะโอนต้องมากกว่า 0!!');
        }

        /// ตรวจจำนวนที่จะโอนว่ามีพอมั้ย ///
        $wallet = Wallet::where('user_id', Auth::id())->where('currency_id', $request->currency_id)->first();
        if($wallet->amount < $request->amount)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'จำนวนที่จะโอนไม่เพียงพอ!!');
        }
        if($request->emailOrOther == null)
        {
            return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'กรุณาใส่ชื่อผู้ที่จะโอนให้ด้วย!!');
        }

        /// กรณีโอนภายใน app ///
        if($request->to == 'Bitinterview')
        {
            $to_user = User::where('email', $request->emailOrOther)->first();
            if($to_user == null)
            {
                return redirect('dashboard')->with('not_ok', 'true')->with('msg', 'ไม่มี email ดังกล่าวในระบบ!!');
            }

            $to_user_wallet = Wallet::where('user_id', $to_user->id)->where('currency_id', $request->currency_id)->first();
            $to_user_wallet->amount += $request->amount;
            $wallet->amount -= $request->amount;

            /// สร้างหลักฐานการโอนทั้ง 2 ฝั่ง ///
            $trade_user = new Trade();
            $trade_user->user_id = Auth::id();
            $trade_user->currency_id = $request->currency_id;
            $trade_user->to_user_id = $to_user->id;
            $trade_user->action = 'transfer';
            $trade_user->amount = $request->amount;

            $trade_to_user = new Trade();
            $trade_to_user->user_id = $to_user->id;
            $trade_to_user->from_user_id = Auth::id();
            $trade_to_user->currency_id = $request->currency_id;
            $trade_to_user->action = 'receive';
            $trade_to_user->amount = $request->amount;

            $to_user_wallet->save();
            $wallet->save();

            $trade_user->save();
            $trade_to_user->save();
        }
        else
        {
            $wallet->amount -= $request->amount;
            $trade_user = new Trade();
            $trade_user->user_id = Auth::id();
            $trade_user->currency_id = $request->currency_id;
            $trade_user->outer_name = $request->emailOrOther;
            $trade_user->action = 'transfer';
            $trade_user->amount = $request->amount;
            $wallet->save();
            $trade_user->save();
        }

        return redirect('dashboard')->with('ok', 'true')->with('msg', 'Transfer success!!');
   
    }
}
