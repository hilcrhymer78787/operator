<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Work;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // ベーシック認証
    public function basic_authentication(Request $request)
    {
        $loginInfo = User::where('email', $request->email)
            ->where('password', $request->password)
            ->select('token')
            ->first();
        if (!isset($loginInfo)) {
            $error['errorMessage'] = 'メールアドレスかパスワードが違います';
            return $error;
        }
        return $loginInfo;
    }

    // ベアラー認証
    public function bearer_authentication(Request $request)
    {
        $loginInfo = User::where('token', $request->header('token'))
            ->select('id', 'name', 'email', 'user_img', 'token', 'user_authority as authority', 'user_salary as salary')
            ->first();
        if (!isset($loginInfo)) {
            $error['errorMessage'] = 'このトークンは有効ではありません';
            return $error;
        }

        // タスク
        $taskArray = [];
        $tasks = Task::where('task_user_id', $loginInfo['id'])
            ->where('task_state', 1)
            ->select('task_id as id', 'task_state as state', 'task_type as type', 'year', 'month')
            ->get();
        foreach ($tasks as $task) {
            array_push($taskArray, $task);
        }

        // 3日以内に出勤 && 日報提出がない
        $notSubmittedreports = Work::where('work_date', '>', date("Y-m-d", strtotime("-3 day")))
            ->where('work_date', '<=', date("Y-m-d"))
            ->where('work_user_id', $loginInfo['id'])
            ->leftjoin('reports', 'works.work_date', '=', 'reports.report_date')
            ->where('report_id', null)
            ->select('work_date as date')
            ->get();
        // 今日22:00以降のは削除
        foreach ($notSubmittedreports as $report) {
            if ($report['date'] . ' 22:00:00' > date("Y-m-d H:i:s")) {
                continue;
            }
            array_push($taskArray, array(
                'date' => $report['date'],
                'type' => 5,
            ));
        }
        $loginInfo['tasks'] = $taskArray;

        if ($loginInfo['authority'] == 1) {
            $loginInfo['admin'] = array(
                'incompleteTaskNum' => Task::where('task_state', 1)
                    ->count(),
                'users' => User::select('id', 'name', 'email', 'user_img', 'token', 'user_authority as authority', 'user_salary as salary')
                    ->get(),
            );
        }

        return $loginInfo;
    }
    public function create(Request $request, User $user)
    {
        if ($request["id"] == 0) {
            // 新規登録
            $userDataCount = count(User::where('email', $request["email"])->get());
            if ($userDataCount != 0) {
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            } else {
                // ユーザー作成
                $user["user_authority"] = $request["authority"];
                $user["name"] = $request["name"];
                $user["email"] = $request["email"];
                $user["password"] = $request["password"];
                $user["user_img"] = $request["user_img"];
                $user["user_salary"] = $request["salary"];
                $user["token"] = $request["email"] . Str::random(100);
                $user->save();
                if ($request['exist_file']) {
                    $request["file"]->storeAs('public/', $request["user_img"]);
                    $user->where("id", $request["id"])->update([
                        "user_img" => $request["user_img"],
                    ]);
                }
                return;
            }
        } else {
            // 編集
            $userInfo = User::where('id', $request['id'])->first();
            $loginInfoCount = count(User::where('email', $request["email"])->get());

            if ($loginInfoCount != 0 && $userInfo['email'] != $request["email"]) {
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            } else {
                $user->where("id", $request['id'])->update([
                    "user_authority" => $request["authority"],
                    "name" => $request["name"],
                    "email" => $request["email"],
                    "user_img" => $request["user_img"],
                    "user_salary" => $request["salary"],
                ]);
                if ($request['exist_file']) {
                    $request["file"]->storeAs('public/', $request["user_img"]);
                    Storage::delete('public/' . $request["img_oldname"]);
                    $user->where("id", $request["id"])->update([
                        "user_img" => $request["user_img"],
                    ]);
                }
                if ($request["password"]) {
                    $user->where("id", $request['id'])->update([
                        "password" => $request["password"],
                    ]);
                }
            }
        }
    }
    public function delete(Request $request)
    {
        User::where('id', $request['id'])->delete();
    }
}
