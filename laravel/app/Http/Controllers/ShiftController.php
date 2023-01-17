<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{

    // 'shift_week_num' => $i,
    // 'shift_week_day' => $day,
    // 'shift_user_id' => $i % 3 + 1,

    public function read()
    {
        $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        foreach ($days as $day) {
            for ($i = 1; $i <= 5; $i++) {
                $data =  Shift::where("shift_week_num", $i)
                ->where("shift_week_day", $day)
                ->first();
                if($data){
                    $return[$i][$day] = $data['shift_user_id'];
                }else{
                    $return[$i][$day] = 0;
                }
            }
        }
        return $return;
    }

    public function create(Request $request)
    {
        $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        foreach ($days as $day) {
            for ($i = 1; $i <= 5; $i++) {
                Shift::where("shift_week_num", $i)
                ->where("shift_week_day", $day)
                ->delete();
                $shift = new shift;
                $shift["shift_week_num"] = $i;
                $shift["shift_week_day"] = $day;
                $shift["shift_user_id"] = intval($request['shifts'][$i][$day]);
                $shift->save();
            }
        }
    }
}
