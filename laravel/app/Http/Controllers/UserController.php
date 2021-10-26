<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\Invitation;
use App\Services\UserService;
use Illuminate\Support\Str;


class UserController extends Controller
{
    // ベーシック認証
    public function basic_authentication(Request $request)
    {
        $loginInfo = User::where('email', $request->email)
        ->where('password',$request->password)
        ->select('token')
        ->first();
        if(!isset($loginInfo)){
            $error['errorMessage'] = 'メールアドレスかパスワードが違います';
            return $error;
        }
        return $loginInfo;
    }

    // ベアラー認証
    public function bearer_authentication(Request $request)
    {
        $loginInfo = User::where('token', $request->header('token'))
        ->select('id', 'name', 'email', 'user_img','token','user_authority as authority','user_salary as salary')
        ->first();
        if(!isset($loginInfo)){
            $error['errorMessage'] = 'このトークンは有効ではありません';
            return $error;
        }
        
        $loginInfo['users'] = User::select('id', 'name', 'email', 'user_img','token','user_authority as authority','user_salary as salary')
        ->get();

        return $loginInfo;
    }
    public function create(Request $request, User $user)
    {
        if($request["id"] == 0){
            // 新規登録
            $userDataCount = count(User::where('email', $request["email"])->get());
            if($userDataCount != 0){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }else{
                // ユーザー作成
                $user["name"] = $request["name"];
                $user["email"] = $request["email"];
                $user["password"] = $request["password"];
                $user["user_img"] = $request["user_img"];
                $user["user_salary"] = $request["salary"];
                $user["token"] = $request["email"].Str::random(100);
                $user->save();
                return;
            }
        }else{
            // 編集
            $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
            if(!isset($loginInfo)){
                $error['errorMessage'] = 'このトークンは有効ではありません';
                return $error;
            }

            $loginInfoCount = count(User::where('email', $request["email"])->get());

            if($loginInfoCount != 0 && $loginInfo['email'] != $request["email"]){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }else{
                $user->where("id", $loginInfo['id'])->update([
                    "name" => $request["name"],
                    "email" => $request["email"],
                    "password" => $request["password"],
                    "user_img" => $request["user_img"],
                    "user_salary" => $request["salary"],
                    "token" => $request["email"].Str::random(100),
                ]);
            }
        }
    }
    public function delete(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
        if(!isset($loginInfo)){
            return $error['errorMessage'] = 'このトークンは有効ではありません';
        }

        User::where('id', $loginInfo['id'])->delete();
    }
}
