<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Services\UserService;
use App\Services\TaskService;


class TaskController extends Controller
{
    public function read(Request $request)
    {
        $users = User::select('id', 'name')
            ->get();

        foreach ($users as $user) {
            for ($n = 1; $n <= 2; $n++) {
                $task = Task::where('task_user_id', $user['id'])
                    ->where('year', $request['year'])
                    ->where('month', $request['month'])
                    ->where('task_type', $n)
                    ->first();
                    $user['type' . $n . '_state'] = $task ? $task['task_state'] : null;
            }
            $user['notSubmittedReportNum'] = (new TaskService())->getNotSubmittedReportNumById($user['id']);
        }
        return $users;
    }
    public function create(Request $request)
    {
        Task::where('year', $request['year'])
            ->where('month', $request['month'])
            ->delete();

        foreach ($request['users'] as $user) {
            for ($n = 1; $n <= 2; $n++) {
                $task = new Task;
                $task["task_user_id"] = $user['id'];
                $task["task_state"] = $user['type' . $n . '_state'];
                $task["task_type"] = $n;
                $task["year"] = $request['year'];
                $task["month"] = $request['month'];
                if ($user['type' . $n . '_state']) {
                    $task->save();
                }
            }
        }
    }
    public function update(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        Task::where("task_user_id", $loginInfo['id'])
            ->where("year", date('Y', strtotime($request['date'])))
            ->where("month", date('m', strtotime($request['date'])))
            ->where("task_type", $request['type'])
            ->update([
                "task_state" => $request['state'],
            ]);
    }
    public function delete(Request $request)
    {
        Task::where('year', $request['year'])
            ->where('month', $request['month'])
            ->delete();
    }
}
