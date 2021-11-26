<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function read(Request $request)
    {
        // $questions = Question::select('question_id as id', 'question_content as content')
        // ->get();

        // foreach($questions as $question){
        //     $question['answer'] = Answer::where('question_id', $question['id'])
        //     ->where('user_id', $request['user_id'])
        //     ->where('year', $request['year'])
        //     ->where('month', $request['month'])
        //     ->select('answer_id as id', 'answer_content as content','year','month','user_id','created_at')
        //     ->first();
        // }

        return Report::get();
    }
}
