<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Parsedown;

class Answer extends Model
{
    protected $fillable = ['body', 'user_id'];

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
     */
    public static function boot()
    {
        parent::boot();

        // questionが作成された時、questions.answers_countカラムに1足す
        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });

        // questionが削除された時、questions.answers_countカラムから1引く
        static::deleted(function ($answer) {
            $question = $answer->question;
            $question->decrement('answers_count');
            // ベストアンサーが削除された場合はquestion.best_answer_idをNULLにする
            if ($question->best_answer_id === $answer->id) {
                $question->best_answer_id = NULL;
                $question->save();
            }
        });
    }

    /**
     * created_atのフォーマットを変更して取得(ex: 1 hour ago, 2 days ago)
     *
     * @return mixed 作成日時
     */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * answerがベストアンサーに選出されていたら、vote-acceptedを返す
     */
    public function getStatusAttribute()
    {
        return $this->id === $this->question->best_answer_id ? 'vote-accepted' : '';
    }
}
