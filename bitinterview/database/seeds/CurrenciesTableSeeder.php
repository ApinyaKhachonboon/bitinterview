<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert(
            array(
                [
                    'name'=>'THB',
                    'type'=>'national'
                ],
                [
                    'name'=>'USD',
                    'type'=>'national'
                ],
                [
                    'name'=>'BTC',
                    'type'=>'crypto'
                ],
                [
                    'name'=>'ETH',
                    'type'=>'crypto'
                ],
                [
                    'name'=>'XRP',
                    'type'=>'crypto'
                ],
                [
                    'name'=>'DOGE',
                    'type'=>'crypto'
                ]
            )
        );
    }
}
