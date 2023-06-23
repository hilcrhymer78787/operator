<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          [
            'name' => 'Yu',
            'email' => 'yusan@gmail.com',
            'token' => 'yusan@gmail.com'.Str::random(100),
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=30',
            'user_salary' => 10000,
            'user_line_group_id' => "",
            'user_authority' => true,
            'joined_company_at' => '2018-11-01',
          ],
          [
            'name' => 'koichi',
            'email' => 'koichi@gmail.com',
            'token' => 'koichi@gmail.com'.Str::random(100),
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=20',
            'user_salary' => 7000,
            'user_line_group_id' => "",
            'user_authority' => false,
            'joined_company_at' => '2018-11-01',
          ],
          [
            'name' => 'hiro',
            'email' => 'hiro@gmail.com',
            'token' => 'hiro@gmail.com'.Str::random(100),
            'password' => 'password',
            'user_img' => 'https://picsum.photos/500/300?image=10',
            'user_salary' => 7000,
            'user_line_group_id' => "",
            'user_authority' => false,
            'joined_company_at' => '2018-11-01',
          ],
      ]);
    }
}
