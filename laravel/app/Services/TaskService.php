<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Work;


class TaskService
{
    public function getTaskArrayById($id)
    {
        // タスク
        $taskArray = [];
        $tasks = Task::where('task_user_id', $id)
            ->where('task_state', 1)
            ->select('task_id as id', 'task_state as state', 'task_type as type', 'year', 'month')
            ->get();
        foreach ($tasks as $task) {
            array_push($taskArray, $task);
        }

        // 3日以内に出勤 && 日報提出がない
        $notSubmittedreports = Work::where('work_date', '>', date("Y-m-d", strtotime("-3 day")))
            ->where('work_date', '<=', date("Y-m-d"))
            ->where('work_user_id', $id)
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
        return $taskArray;
    }
    public function getNotSubmittedReportNumById($id)
    {
        // 3日以内に出勤 && 日報提出がない
        $notSubmittedreports = Work::where('work_date', '>', date("Y-m-d", strtotime("-3 day")))
            ->where('work_date', '<=', date("Y-m-d"))
            ->where('work_user_id', $id)
            ->leftjoin('reports', 'works.work_date', '=', 'reports.report_date')
            ->where('report_id', null)
            ->select('work_date as date')
            ->get();
        
        $notSubmittedReportNum = 0;
        // 今日22:00以降のは削除
        foreach ($notSubmittedreports as $report) {
            if ($report['date'] . ' 22:00:00' > date("Y-m-d H:i:s")) {
                continue;
            }
            $notSubmittedReportNum++;
        }
        return $notSubmittedReportNum;
    }
}
