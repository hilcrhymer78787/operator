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
    public function login_info(Request $request)
    {
        // 個人情報取得
        if($request->token){
            // トークン認証
            $loginInfo = User::where('token', $request->token)
            ->select('id', 'name', 'email', 'user_img','token','user_authority as authority')
            ->first();
            if(!isset($loginInfo)){
                $error['errorMessage'] = 'このトークンは有効ではありません';
                return $error;
            }
        }elseif($request->email){
            // ベーシック認証
            $loginInfo = User::where('email', $request->email)
            ->where('password',$request->password)
            ->select('id', 'name', 'email', 'user_img','token','user_authority')
            ->first();
            if(!isset($loginInfo)){
                $error['errorMessage'] = 'メールアドレスかパスワードが違います';
                return $error;
            }
        }
        
        $loginInfo['users'] = User::select('id', 'name', 'email', 'user_img','token','user_authority')
        ->get();

        return $loginInfo;
    }
    public function read()
    {
        return User::get();
    }
    public function create(Request $request, Room $room, User $user, Invitation $invitation)
    {
        if($request["id"] == 0){
            // 新規登録
            $userDataCount = count(User::where('email', $request["email"])->get());
            if($userDataCount != 0){
                $error['errorMessage'] = 'このメールアドレスは既に登録されています';
                return $error;
            }
            else{

                // 部屋を作成

                $roomToken = date('Y-m-d H:i:s').Str::random(100);

                $room["room_name"] = "マイルーム";
                $room["room_img"] = "https://picsum.photos/500/300?image=40";
                $room["room_token"] = $roomToken;
                $room->save();
    
                $roomId = Room::where('room_token', $roomToken)->get()[0]->room_id;

                // ユーザー作成
                $userToken = $request["email"].Str::random(100);

                $user["name"] = $request["name"];
                $user["email"] = $request["email"];
                $user["password"] = $request["password"];
                $user["user_img"] = $request["user_img"];
                $user["token"] = $userToken;
                $user["user_room_id"] = $roomId;
                $user->save();

                $loginInfo = (new UserService())->getLoginInfoByToken($userToken);

                // 招待
                $invitation['invitation_room_id'] = $roomId;
                $invitation['invitation_from_user_id'] = $loginInfo['id'];
                $invitation['invitation_to_user_id'] = $loginInfo['id'];
                $invitation['invitation_status'] = 2;
                $invitation->save();

                return;
            }
        }else{
            // 編集
            $loginInfo = (new UserService())->getLoginInfoByToken($request->token);
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
                    "token" => $request["email"].Str::random(100),
                ]);
            }
        }
    }
    public function updateRoomId(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->token);
        if(!isset($loginInfo)){
            return $error['errorMessage'] = 'このトークンは有効ではありません';
        }
        User::where("id", $loginInfo['id'])->update([
            "user_room_id" => $request["room_id"],
        ]);
    }
    public function delete(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->token);
        if(!isset($loginInfo)){
            return $error['errorMessage'] = 'このトークンは有効ではありません';
        }

        User::where('id', $loginInfo['id'])->delete();
    }
}
