<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * emailとpasswordが未入力の場合ログインページにリダレクト
     */
    public function testRedirectToLoginPage()
    {
        $this->visit('/login')
            ->type('', 'email')
            ->type('', 'password')
            ->press('Login')
            ->seePageIs('/login');
    }

    /**
     * emailとpasswordのバリデーションチェック
     */
    public function testLoginValidationCheck()
    {
        $this->visit('/login')
            ->type('aaa', 'email')
            ->type('bbb', 'password')
            ->press('Login')
            ->seePageIs('/login');
    }

    /**
     * ログイン実施テスト
     */
    public function testLogin()
    {
        // メンバーをDB登録
        $member = factory(User::class)->make([
            'id'        => 1,
            'email'     => 'loginTest@example.com',
            'password'  => bcrypt('password')
        ]);
        $member->save();
        $this->visit('/login')
            ->type('loginTest@example.com', 'email')
            ->type('password', 'password')
            ->press('Login')
            ->seePageIs('/my-posts');

    }
}
