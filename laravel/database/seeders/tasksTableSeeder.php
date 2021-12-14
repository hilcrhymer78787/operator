<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            [
                'task_user_id' => 1,
                'task_state' => 1,
                'task_type' => 1,
                'year' => 2021,
                'month' => 12,
            ],
            [
                'task_user_id' => 1,
                'task_state' => 1,
                'task_type' => 2,
                'year' => 2021,
                'month' => 11,
            ],
            [
                'task_user_id' => 1,
                'task_state' => 1,
                'task_type' => 2,
                'year' => 2021,
                'month' => 12,
            ],
            [
                'task_user_id' => 1,
                'task_state' => 1,
                'task_type' => 2,
                'year' => 2022,
                'month' => 1,
            ],
            [
                'task_user_id' => 2,
                'task_state' => 2,
                'task_type' => 2,
                'year' => 2021,
                'month' => 12,
            ],
        ]);
    }
}