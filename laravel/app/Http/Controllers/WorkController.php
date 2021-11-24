<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;

class WorkController extends Controller
{
    public function read(Request $request)
    {
        $works = Work::whereYear('work_date', $request['year'])
        ->whereMonth('work_date', $request['month'])
        ->leftjoin('users', 'works.work_user_id', '=', 'users.id')
        ->select('work_id as id', 'work_salary as salary', 'work_user_id as user_id','name','work_date')
        ->get();

        return $works;

    }
    public function create(Request $request)
    {
        Work::where('work_date', $request["date"])
        ->delete();

        foreach($request['works'] as $work){
            $newWork = new Work;
            $newWork["work_date"] = $request["date"];
            $newWork["work_user_id"] = $work["user_id"];
            $newWork["work_salary"] = $work["salary"];
            $newWork->save();
        }
    }
    public function delete(Request $request)
    {
        Work::where('work_date', $request["date"])
        ->delete();
    }
}
