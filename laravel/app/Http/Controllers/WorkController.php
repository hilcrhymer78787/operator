<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Shift;
use App\Models\User;
use App\Models\Task;

class WorkController extends Controller
{
    public function read(Request $request)
    {
        $works = Work::whereYear('work_date', $request['year'])
            ->whereMonth('work_date', $request['month'])
            ->leftjoin('users', 'works.work_user_id', '=', 'users.id')
            ->select('work_id as id', 'work_salary as salary', 'work_user_id as user_id', 'name', 'work_date')
            ->get();

        return $works;
    }
    public function create(Request $request)
    {

        // シフト確認タスク発行
        if ($request["shiftCheck"]) {
            $year = date('Y', strtotime($request["date"]));
            $month = date('m', strtotime($request["date"]));
            $oldWorks = Work::where('work_date', $request["date"])
                ->get();
            $workUserIds = array();
            foreach ($oldWorks as $work) {
                $workUserIds[] = $work['work_user_id'];
            }
            foreach ($request["works"] as $work) {
                $workUserIds[] = $work['user_id'];
            }
            $notDuplicateElements = array_filter(array_count_values($workUserIds), function ($v) {
                return $v === 1;
            });
            $chengedUserIds = array_keys($notDuplicateElements);
            foreach ($chengedUserIds as $chengedUserId) {
                $type = 2;
                Task::where('task_user_id', $chengedUserId)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('task_type', $type)
                    ->delete();
                $task = new Task;
                $task["task_user_id"] = $chengedUserId;
                $task["task_state"] = 1;
                $task["task_type"] = $type;
                $task["year"] = $year;
                $task["month"] = $month;
                $task->save();
            }
        };

        Work::where('work_date', $request["date"])
            ->delete();

        foreach ($request['works'] as $work) {
            $newWork = new Work;
            $newWork["work_date"] = $request["date"];
            $newWork["work_user_id"] = $work["user_id"];
            $newWork["work_salary"] = $work["salary"];
            $newWork->save();
        }
        return $request["shiftCheck"] ? $chengedUserIds : [];
    }
    public function all(Request $request)
    {


        function get_numberx_weekday($y, $m, $n, $w)
        {

            // 曜日テーブルから曜日番号を取得
            $ar_wd = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
            $search_week_day = array_search($w, $ar_wd);
            $lastDay = date('t', mktime(0, 0, 0, $m, 1, $y));

            // 指定曜日の最初の日付を取得して指定週の日付を算出
            $first_weekday = date("w", mktime(0, 0, 0, $m, 1, $y));
            $result_day = $search_week_day - $first_weekday + 1;
            if ($result_day < 1) {
                $result_day += 7;
            }
            $result_day = $result_day + ($n - 1) * 7;
            if ($result_day > $lastDay) {
                return 0;
            }
            return $result_day;
        }

        $lastDay = date('t', mktime(0, 0, 0, $request['month'], 1, $request['year']));
        for ($j = 1; $j <= $lastDay; $j++) {
            Work::where('work_date', $request['year'] . '-' . $request['month'] . '-' . $j)
                ->delete();
        }

        $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        foreach ($days as $day) {
            for ($i = 1; $i <= 5; $i++) {


                $data =  Shift::where("shift_week_num", $i)
                    ->where("shift_week_day", $day)
                    ->first();

                $targetDay = get_numberx_weekday($request['year'], $request['month'], $i, $day);

                $user = User::where('id', $data['shift_user_id'])
                    ->first();

                if ($data && $data['shift_user_id'] && $targetDay && $user) {

                    $newWork = new Work;
                    $newWork["work_date"] = $request['year'] . '-' . $request['month'] . '-' . $targetDay;
                    $newWork["work_user_id"] = $data['shift_user_id'];
                    $newWork["work_salary"] = $user['user_salary'];
                    $newWork->save();
                }
            }
        }
    }
    public function delete(Request $request)
    {
        // シフト確認タスク発行
        if ($request["shiftCheck"]) {
            $year = date('Y', strtotime($request["date"]));
            $month = date('m', strtotime($request["date"]));
            $oldWorks = Work::where('work_date', $request["date"])
                ->get();
            $workUserIds = array();
            foreach ($oldWorks as $work) {
                $workUserIds[] = $work['work_user_id'];
            }
            $chengedUserIds = $workUserIds;
            foreach ($workUserIds as $chengedUserId) {
                $type = 2;
                Task::where('task_user_id', $chengedUserId)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('task_type', $type)
                    ->delete();
                $task = new Task;
                $task["task_user_id"] = $chengedUserId;
                $task["task_state"] = 1;
                $task["task_type"] = $type;
                $task["year"] = $year;
                $task["month"] = $month;
                $task->save();
            }
        };
        Work::where('work_date', $request["date"])
            ->delete();

        return $request["shiftCheck"] ? $chengedUserIds : [];
    }
}
