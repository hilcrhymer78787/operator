<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Answer;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 51; $i++) {
            Answer::insert([
                [
                    'user_id' => 1,
                    'question_id' => $i,
                    'answer_content' => $i%5,
                    'year' => 2021,
                    'month' => 11,
                ],
            ]);
            Answer::insert([
                [
                    'user_id' => 2,
                    'question_id' => $i,
                    'answer_content' => 2,
                    'year' => 2021,
                    'month' => 11,
                ],
            ]);
            Answer::insert([
                [
                    'user_id' => 1,
                    'question_id' => $i,
                    'answer_content' => 5-$i%5,
                    'year' => 2021,
                    'month' => 12,
                ],
            ]);
            Answer::insert([
                [
                    'user_id' => 3,
                    'question_id' => $i,
                    'answer_content' => 3,
                    'year' => 2021,
                    'month' => 12,
                ],
            ]);
        }
    }
}
