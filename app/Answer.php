<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * Answerに紐づくquestionを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Answerに紐づくuserを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * body内のHTMLが展開されて取得
     *
     * @return string 本文
     */
    public function getBodyHtmlAttribute()
    {
        return Parsedown::instance()->text($this->body);
    }

    /**
     * answerが作成された時に発火するイベント
     * questions.answers_countカラムに1足す
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });
    }
}
