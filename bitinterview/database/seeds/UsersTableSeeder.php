<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [];
        for($i = 1; $i <= 100; $i++)
        {
            $user = [];
            $user['name'] = 'Person'.$i;
            $user['email'] = 'person'.$i.'@gmail.com';
            $user['password'] = '$2y$10$0r6a.KfKj7qjFxRoQTJw2euRos0CQvLD6xRhiJY/yat7QmuiyZw8e';
            $users[$i - 1] = $user;
        }
        DB::table('users')->insert(
            // array(
            //     [
            //         'name'=>'manude',
            //         'email'=>'omanudeo@gmail.com',
            //         'password'=>'$2y$10$0r6a.KfKj7qjFxRoQTJw2euRos0CQvLD6xRhiJY/yat7QmuiyZw8e'
            //     ],
            //     [
            //         'name'=>'Peearpar',
            //         'email'=>'phusit_sawat@hotmail.com',
            //         'password'=>'$2y$10$0r6a.KfKj7qjFxRoQTJw2euRos0CQvLD6xRhiJY/yat7QmuiyZw8e'
            //     ]
            // )
            $users
        );
    }
}
