<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        foreach ($days as $day) {
            for ($j = 1; $j <= 5; $j++) {
                Shift::create([
                    'shift_week_num' => $j,
                    'shift_week_day' => $day,
                    'shift_user_id' => $j % 3 + 1,
                ]);
            }
        }
    }
}
