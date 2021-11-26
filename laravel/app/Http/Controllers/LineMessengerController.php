<?php

namespace App\Http\Controllers;

use App\Models\Work;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                // $reply_message = $request['events'][0]['source']['groupId'];;
                // ユーザーにメッセージを返す
                // $bot->replyText($reply_token, $reply_message);
                break;
            case 'join':
                // 送信するメッセージの設定
                $reply_message = $request['events'][0]['source']['groupId'];;
                // ユーザーにメッセージを返す
                $bot->replyText($reply_token, $reply_message);
                break;
        }
    }
    public function message(Request $request)
    {
        $message = $request['text'];

        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
        $textMessageBuilder = new TextMessageBuilder($message);
        $response = $bot->pushMessage(config('services.line.group_id'), $textMessageBuilder);

        echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
    public static function today_worker()
    {
        $works = Work::where('work_date', date('Y-m-d'))
            ->leftjoin('users', 'works.work_user_id', '=', 'users.id')
            ->select('name', 'work_date')
            ->get();

        $names = '';
        foreach ($works as $work) {
            $names = $names . '「' . $work['name'] . '」';
        }

        $date = date("Y年m月d日");

        $message = "本日${date}の出演者は\n\n${names}さんです。\n\nよろしくお願いいたします。";

        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
        $textMessageBuilder = new TextMessageBuilder($message);
        $response = $bot->pushMessage(config('services.line.group_id'), $textMessageBuilder);

        echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
    public static function remind_report()
    {
        $works = Work::where('work_date', date('Y-m-d'))
            ->leftjoin('users', 'works.work_user_id', '=', 'users.id')
            ->select('name', 'work_date')
            ->get();

        $names = '';
        foreach ($works as $work) {
            $names = $names . '「' . $work['name'] . '」';
        }

        $message = "${names}さん出勤お疲れ様です！\n\n日報の提出をよろしくお願いします！";

        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
        $textMessageBuilder = new TextMessageBuilder($message);
        $response = $bot->pushMessage(config('services.line.group_id'), $textMessageBuilder);

        echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
}
