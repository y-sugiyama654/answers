<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * questionにfavoriteを追加する
     *
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Question $question)
    {
        $question->favorites()->attach(Auth::id());

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return back();
    }

    /**
     * questionのfavoriteを削除する
     *
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Question $question)
    {
        $question->favorites()->detach(Auth::id());

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return back();
    }
}
