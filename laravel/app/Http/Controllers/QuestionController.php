<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function read(Request $request)
    {
        return Question::select('question_id as id','question_content as content')->get();
    }
    public function create(Request $request, Question $question)
    {
        if($request['id']){
            $question->where('question_id', $request['id'])->update([
                'question_content' => $request['content'],
            ]);
            return;
        }else{
            $question['question_content'] = $request['content'];
            $question->save();
            return;
        }
    }
    public function delete(Request $request, Question $question)
    {
        Question::where('question_id', $request['id'])->delete();
        return;
    }
}
