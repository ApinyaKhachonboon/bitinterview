<?php

use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wallets = [];
        $k = 0;
        for($i = 1; $i <= 100; $i++)
        {
            for($j = 1; $j <= 2; $j++)
            {
                if(rand(1,10) > 4)
                {
                    $wallet = [];
                    $wallet['user_id'] = $i;
                    $wallet['currency_id'] = $j;
                    $wallet['amount'] = rand(500, 10000000);
                    $wallets[$k] = $wallet;
                    $k++;
                }
            }
            for($j = 3; $j <= 6; $j++)
            {
                if(rand(1,10) > 4)
                {
                    $wallet = [];
                    $wallet['user_id'] = $i;
                    $wallet['currency_id'] = $j;
                    $wallet['amount'] = rand(1, 10000000) * 0.000001;
                    $wallets[$k] = $wallet;
                    $k++;
                }
            }
        }
        DB::table('wallets')->insert(
            $wallets
            // array(
            //     [
            //         'user_id'=> 1,
            //         'currency_id'=> 1,
            //         'amount'=> 500000
            //     ],
            //     [
            //         'user_id'=> 1,
            //         'currency_id'=> 2,
            //         'amount'=> 5000
            //     ],
            //     [
            //         'user_id'=> 1,
            //         'currency_id'=> 3,
            //         'amount'=> 0.001
            //     ],
            //     [
            //         'user_id'=> 2,
            //         'currency_id'=> 1,
            //         'amount'=> 500000
            //     ],
            //     [
            //         'user_id'=> 2,
            //         'currency_id'=> 2,
            //         'amount'=> 5000
            //     ],
            //     [
            //         'user_id'=> 2,
            //         'currency_id'=> 3,
            //         'amount'=> 0.078
            //     ],
            // )
            
        );
    }
}
