<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Parsedown;

class Question extends Model
{
    use VotableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'body'];

    /**
     * アクセサで取得したプロパティをvue用に追加
     *
     * @var string[]
     */
    protected $appends = ['created_date', 'is_favorited', 'favorites_count', 'body_html'];

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
        return clean($this->bodyHtml());
    }

    /**
     * questionに紐づくanswerを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('votes_count', 'DESC');
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

    /**
     * questionに紐づくfavoritesを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * userに紐づくfavoriteが1つ以上存在するか確認
     *
     * @return bool
     */
    public function isFavorited()
    {
        return $this->favorites()->where('user_id', request()->user()->id())->count() > 0;
    }

    /**
     * userに紐づくfavoriteが1つ以上存在するか確認
     *
     * @return bool
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * userに紐づくfavoriteのカウントを取得
     *
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    /**
     * サニタイズと文字数制限の処理
     *
     * @return string body本文
     */
    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    /**
     * bodyを処理して返却
     *
     * @param $length 文字数
     * @return string body本文
     */
    private function excerpt($length)
    {
        return Str::limit(strip_tags($this->bodyHtml()), $length);
    }

    /**
     * htmlタグを展開
     *
     * @return string body本文
     */
    private function bodyHtml()
    {
        return Parsedown::instance()->text($this->body);
    }
}
