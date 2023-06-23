<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Work;
use App\Services\UserService;
use App\Services\LineService;

class ReportController extends Controller
{
    public function read(Request $request)
    {
        $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));

        $report['main'] = Report::where('report_date', $request['date'])
            ->select('report_id as id', 'report_date as date', 'report_content as content')
            ->first();
        if (!$report['main']) {
            $report['main'] = 'notFound';
        }

        // 編集権限（出勤者 && 三日以内 && 当日22:00以降）
        $worker = Work::where('work_date', $request['date'])
            ->where('work_user_id', $loginInfo['id'])
            ->first();
        $deadline = date("Y-m-d", strtotime("-3 day")) < $request['date'];
        $startline = $request['date'] . ' 22:00:00' < date("Y-m-d H:i:s") ? true : false;
        $report['editable'] = $worker && $deadline && $startline;

        return $report;
    }
    public function create(Request $request, Report $report)
    {
        $report = new Report;
        $report["report_date"] = $request['data']["date"];
        $report["report_content"] = $request['data']["content"];
        $report->save();

        $lineReport = Report::where('report_date',$request['data']["date"])
        ->select('report_content as content')
        ->first();

        $message = $request['data']["name"]."さんが日報を送信しました！\n\n".$lineReport['content'];
        (new LineService())->lineMessage($message);
        // (new LineService())->lineMessage($message, $request['data']["lineGroupId"]);

        return;
    }
    public function edit(Request $request, Report $report)
    {
        $report->where("report_id", $request['data']['id'])->update([
            "report_date" => $request['data']["date"],
            "report_content" => $request['data']["content"],
        ]);
        return;
    }
    public function delete(Request $request, Report $report)
    {
        Report::where('report_id', $request["id"])
            ->delete();
        return;
    }
}
