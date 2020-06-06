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
}
