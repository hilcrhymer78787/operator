<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Task;
use App\Models\Question;

class AnswerController extends Controller
{
    public function read(Request $request)
    {
        $questions = Question::select('question_id as id', 'question_content as content', 'question_reason as reason', 'question_important as important')
        ->get();

        foreach($questions as $question){
            $question['answer'] = Answer::where('question_id', $question['id'])
            ->where('user_id', $request['user_id'])
            ->where('year', $request['year'])
            ->where('month', $request['month'])
            ->select('answer_id as id', 'answer_content as content','year','month','user_id','created_at')
            ->first();
        }

        return $questions;
    }
    public function create(Request $request)
    {
        Answer::where('year', $request["year"])
        ->where('month', $request["month"])
        ->where('user_id', $request["user_id"])
        ->delete();

        foreach($request['questions'] as $question){
            $answer = new Answer;
            $answer["user_id"] = $request["user_id"];
            $answer["question_id"] = $question['id'];
            $answer["answer_content"] = $question['answer']['content'];
            $answer["year"] = $request["year"];
            $answer["month"] = $request["month"];
            $answer->save();
        }

        Task::where("task_user_id", $request["user_id"])
        ->where("year", $request["year"])
        ->where("month", $request["month"])
        ->where("task_type", 1)
        ->update([
            "task_state" => 2,
        ]);

        return;
    }
    public function delete(Request $request)
    {
        Answer::where('year', $request["year"])
        ->where('month', $request["month"])
        ->where('user_id', $request["user_id"])
        ->delete();
        return;
    }
}
