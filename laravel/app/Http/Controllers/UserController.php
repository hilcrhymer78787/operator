<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Services\TaskService;
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
            ->select('id', 'name', 'email', 'user_img', 'token', 'joined_company_at', 'user_authority as authority', 'user_salary as salary', 'user_line_group_id as lineGroupId', 'active')
            ->first();
        if (!isset($loginInfo)) {
            $error['errorMessage'] = 'このトークンは有効ではありません';
            return $error;
        }

        $loginInfo['tasks'] =  (new TaskService())->getTaskArrayById($loginInfo['id']);

        $users = User::select('id', 'name', 'email', 'user_img', 'token', 'joined_company_at', 'user_authority as authority', 'user_salary as salary', 'user_line_group_id as lineGroupId', 'active')
            ->orderByDesc('active')
            ->get();

        $incompleteTaskNum = 0;
        foreach ($users as $user) {
            $incompleteTaskNum = $incompleteTaskNum + count((new TaskService())->getTaskArrayById($user['id']));
        }

        if ($loginInfo['authority'] == 1) {
            $loginInfo['admin'] = array(
                'incompleteTaskNum' => $incompleteTaskNum,
                'users' => $users,
            );
        }

        $loginInfo['users'] = User::select('id', 'name', 'user_img', 'user_authority as authority', 'active')->get();

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
                $user["active"] = $request["active"];
                $user["name"] = $request["name"];
                $user["email"] = $request["email"];
                $user["password"] = $request["password"];
                $user["user_img"] = $request["user_img"];
                $user["user_salary"] = $request["salary"];
                $user["user_line_group_id"] = $request["lineGroupId"];
                $user["joined_company_at"] = $request["joined_company_at"];
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
                    "active" => $request["active"],
                    "name" => $request["name"],
                    "email" => $request["email"],
                    "user_img" => $request["user_img"],
                    "user_salary" => $request["salary"],
                    "user_line_group_id" => $request["lineGroupId"],
                    "joined_company_at" => $request["joined_company_at"],
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
