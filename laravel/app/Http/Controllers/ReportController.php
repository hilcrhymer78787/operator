<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
  public function read(Request $request)
  {
    $report = Report::where('report_date', $request['date'])
      ->select('report_id as id', 'report_date as date', 'report_content as content')
      ->first();

    return $report ? $report : 'notFound';
  }
  public function create(Request $request, Report $report)
  {
    $report = new Report;
    $report["report_date"] = $request['data']["date"];
    $report["report_content"] = $request['data']["content"];
    $report->save();
    return;
  }
  public function edit(Request $request, Report $report)
  {
    $report->where("report_id", $request['data']['id'])->update([
        "report_date" => $request['data']["date"],
        "report_content" => $request['data']["content"],
    ]);
  }
  public function delete(Request $request, Report $report)
  {
    // $report = new Report;
    // $report["report_date"] = $request['data']["date"];
    // $report["report_content"] = $request['data']["content"];
    // $report->save();
    // return;
  }
}

