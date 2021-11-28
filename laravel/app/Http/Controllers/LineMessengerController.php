<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\User;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Services\LineService;
use App\Services\TaskService;
use Illuminate\Http\Request;

class LineMessengerController extends Controller
{
    public function webhook(Request $request)
    {
        // そこからtypeをとりだし、$typeに代入
        $type = $request['events'][0]['type'];

        // replyTokenを取得
        $reply_token = $request['events'][0]['replyToken'];

        // LINEBOTSDKの設定
        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);

        // メッセージが送られた場合、$typeは'message'となる。その場合処理実行。
        switch ($type) {
            case 'message':
                // 送信するメッセージの設定
                // $message = $request['events'][0]['source']['groupId'];;
                // ユーザーにメッセージを返す
                // $bot->replyText($reply_token, $message);
                break;
            case 'join':
                $group_id = $request['events'][0]['source']['groupId'];
                $message = "はじめまして！\nこの度、勤怠管理や日報の管理を担当することになりました、立花藍です！\n至らない部分も多いかとは存じますが、今後ともよろしくお願いいたします！";
                // ユーザーにメッセージを返す
                $bot->replyText($reply_token, $message);
                $bot->replyText($reply_token, $group_id);
                break;
        }
    }
    public function message(Request $request)
    {
        $message = $request['text'];
        (new LineService())->lineMessage($message);
    }
    public static function today_worker()
    {
        $works = Work::where('work_date', date('Y-m-d'))
            ->leftjoin('users', 'works.work_user_id', '=', 'users.id')
            ->select('name', 'work_date')
            ->get();

        if (!count($works)) {
            return;
        }

        $names = '';
        foreach ($works as $work) {
            $names = $names . '「' . $work['name'] . '」';
        }

        $date = date("m/d");
        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        $day_of_week = $week[date("w")];
        $message = "おはようございます！\n本日${date}（${day_of_week}）の出演者は\n\n${names}さんです！\n\nよろしくお願いいたします！";
        (new LineService())->lineMessage($message);
    }
    public static function remind_report()
    {
        $works = Work::where('work_date', date('Y-m-d'))
            ->leftjoin('users', 'works.work_user_id', '=', 'users.id')
            ->select('name', 'work_date')
            ->get();

        if (!count($works)) {
            return;
        }

        $names = '';
        foreach ($works as $work) {
            $names = $names . '「' . $work['name'] . '」';
        }

        $message = "${names}さん出勤お疲れ様です！\n\n日報の提出をよろしくお願いします！";
        (new LineService())->lineMessage($message);
    }
    public static function incomplete_task()
    {
        $users = User::select('id', 'name')
            ->get();

        $count_text = '';
        foreach ($users as $user) {
            $num = count((new TaskService())->getTaskArrayById($user['id']));
            if (!$num) {
                continue;
            }
            $count_text = $count_text . $user['name'] . 'さん：' . $num . "件\n\n";
        }

        if (!$count_text) {
            return;
        }
        $message = "お疲れ様です！\n未完了のタスクを報告いたします！\n\n${count_text}以上です！\nよろしくお願いいたします！";
        (new LineService())->lineMessage($message);
    }
}
