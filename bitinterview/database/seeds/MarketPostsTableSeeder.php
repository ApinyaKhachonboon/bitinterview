<?php

use Illuminate\Database\Seeder;

class MarketPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [];
        $k = 0;
        for($i = 1; $i <= 100; $i++)
        {
            for($j = 3; $j <= 6; $j++)
            {
                if(rand(1,10) > 4)
                {
                    $data = [];
                    $data['user_id'] = $i;
                    $data['currency_id'] = $j;
                    $data['fiat_id'] = rand(1, 2);
                    if($j == 3)
                    {
                        if($data['fiat_id'] == 1)
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 50;
                                $data['price'] = rand(156000000, 158000000) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 50;
                                $data['price'] = rand(153000000, 155000000) * 0.01;
                            }
                                
                        }
                        else
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 2;
                                $data['price'] = rand(4600000, 4800000) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 2;
                                $data['price'] = rand(4300000, 4500000) * 0.01;
                            }
                        }     
                    }
                    if($j == 4)
                    {
                        if($data['fiat_id'] == 1)
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 50;
                                $data['price'] = rand(12000000, 14000000) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 50;
                                $data['price'] = rand(9000000, 11000000) * 0.01;
                            } 
                        }
                        else
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 2;
                                $data['price'] = rand(340000, 360000) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 2;
                                $data['price'] = rand(310000, 330000) * 0.01;
                            }
                        }
                    }
                    if($j == 5)
                    {
                        if($data['fiat_id'] == 1)
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 50;
                                $data['price'] = rand(2800, 3000) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 50;
                                $data['price'] = rand(2500, 2700) * 0.01;
                            } 
                        }
                        else
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 2;
                                $data['price'] = rand(80, 100) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 2;
                                $data['price'] = rand(50, 70) * 0.01;
                            }
                        }
                    }
                    if($j == 6)
                    {
                        if($data['fiat_id'] == 1)
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 50;
                                $data['price'] = rand(400, 600) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 50;
                                $data['price'] = rand(100, 300) * 0.01;
                            } 
                        }
                        else
                        {
                            if(rand(1, 2) == 1)
                            {
                                $data['action'] = 'buy';
                                $data['min'] = 2;
                                $data['price'] = rand(13, 15) * 0.01;
                            }
                            else
                            {
                                $data['action'] = 'sell';
                                $data['min'] = 2;
                                $data['price'] = rand(10, 12) * 0.01;
                            }
                        }
                    }
                    
                    $data['amount'] = rand(1000, 100000000) * 0.00000001;
                    if($j == 5 || $j == 6)
                    {
                        $data['amount'] = rand(1000, 100000000) * 0.00001;
                    }
                    $data['max'] = $data['price'] * $data['amount'];
                    $datas[$k] = $data;
                    $k++;
                }
            }
        }
        DB::table('market_posts')->insert(
            $datas
        );
    }
}
