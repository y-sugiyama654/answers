<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Parsedown;

class Question extends Model
{
    protected $fillable = ['title', 'body'];

    /**
     * ユーザーの取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * タイトル名からスラグを設定
     *
     * @param $value タイトル名
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * questionsのリンクを取得
     *
     * @return string questionsのリンク
     */
    public function getUrlAttribute()
    {
        return route('questions.show', $this->slug);
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
     * questionのステータスを状況事に取得
     *
     * @return string ステータス
     */
    public function getStatusAttribute()
    {
        if ($this->answers_count > 0) {
            if ($this->best_answer_id) {
                return 'answered-accepted';
            }
            return 'answered';
        }
        return 'unanswered';
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
     * questionに紐づくanswerを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * アンサーとベストアンサーを紐付ける
     *
     * @param Answer $answer answer情報
     */
    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }
}
