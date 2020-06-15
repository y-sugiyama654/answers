<?php
namespace App;

trait VotableTrait
{
    /**
     * このvotableを付けた全userの取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function votes()
    {
        return $this->morphToMany(User::class, 'votable');
    }

    /**
     * answerの投票に1加算
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function upVotes()
    {
        return $this->votes()->wherePivot('vote', 1);
    }

    /**
     * answerの投票に1減算
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function downVotes()
    {
        return $this->votes()->wherePivot('vote', -1);
    }
}
