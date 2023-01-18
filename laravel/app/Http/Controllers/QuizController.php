<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Quiz;

class QuizController extends Controller
{
    public function read()
    {
        return Quiz::select('quiz_id as id','quiz_title as title','quiz_content as content')->get();
    }
    public function create(Request $request, Quiz $quiz)
    {
        if ($request['id']) {
            $quiz->where('quiz_id', $request['id'])->update([
                'quiz_title' => $request['title'],
                'quiz_content' => $request['content'],
            ]);
            return;
        } else {
            $quiz['quiz_title'] = $request['title'];
            $quiz['quiz_content'] = $request['content'];
            $quiz->save();
            return;
        }
    }
    public function delete(Request $request, Quiz $quiz)
    {
        Quiz::where('quiz_id', $request['id'])->delete();
        return;
    }
}
