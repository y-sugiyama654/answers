<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Http\Request;

class VoteAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * answerの投票
     *
     * @param Answer $answer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Answer $answer)
    {
        $vote = (int) request()->vote;
        $votesCount = auth()->user()->voteAnswer($answer, $vote);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Thanks for the feedback',
                'votesCount' => $votesCount,
            ]);
        }

        return back();
    }
}
