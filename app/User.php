<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * アクセサで取得したプロパティをvue用に追加
     *
     * @var string[]
     */
    protected $appends = ['url', 'avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * questionを取得する
     *
     * @return mixed
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * userのリンクを取得
     *
     * @return string questionsのリンク
     */
    public function getUrlAttribute()
    {
        //return route('questions.show', $this->id);
        return '#';
    }

    /**
     * userに紐づくanswerを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * userにアバター画像を取得
     */
    public function getAvatarAttribute()
    {
        $email = $this->email;
        $size = 32;

        return $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
    }

    /**
     * userに紐づくfavoritesを取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps;
    }

    /**
     * このvotableを付けた全questionの取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'votable');
    }

    /**
     * このvotableを付けた全answerの取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'votable');
    }

    /**
     * question投票機能
     *
     * @param Question $question
     * @param $vote
     */
    public function voteQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->voteQuestions();

        return $this->_vote($voteQuestions, $question, $vote);
    }

    /**
     * answerの投票機能
     *
     * @param Question $question
     * @param $vote
     */
    public function voteAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();

        return $this->_vote($voteAnswers, $answer, $vote);
    }

    /**
     * 投票機能
     *
     * @param $relationship
     * @param $model
     * @param $vote
     */
    private function _vote($relationship, $model, $vote)
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        } else {
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');

        $model->votes_count = $upVotes + $downVotes;
        $model->save();

        return $model->votes_count;
    }
}
