<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class worksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  \App\Models\Work::factory(100)->create();
        for($day = 1; $day <= 20; $day++){
            DB::table('works')->insert([
                [
                  'work_date'=>'2021-11-'.$day,
                  'work_user_id' => $day % 2 + 1,
                  'work_salary' => 5000,
                ],
            ]);
        }
        for($day = 1; $day <= 10; $day++){
            DB::table('works')->insert([
                [
                  'work_date'=>'2021-11-'.$day,
                  'work_user_id' => 3,
                  'work_salary' => 10000,
                ],
            ]);
        }
        for($day = 1; $day <= 20; $day++){
            DB::table('works')->insert([
                [
                  'work_date'=>'2021-12-'.$day,
                  'work_user_id' => $day % 2 + 1,
                  'work_salary' => 5000,
                ],
            ]);
        }
    }
}