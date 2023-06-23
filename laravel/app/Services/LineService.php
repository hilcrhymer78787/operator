<?php

namespace App\Services;

use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Mockery\Undefined;

class LineService
{

    public function lineMessage($message, $groupId = null)
    {
        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
        $textMessageBuilder = new TextMessageBuilder($message);
        $response = $bot->pushMessage($groupId ?? config('services.line.group_id'), $textMessageBuilder);
        echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
}