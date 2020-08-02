<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    /**
     * ログイン処理
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $request->request->add([
            'grant_type' => 'password',
            'client_id' => 2,
            'client_secret' => 'zBVuWXRZGZlvutu52htUO7wmhg0Q6rJdYH0vncyn',
            'username' => $request->username,
            'password' => $request->password,
        ]);

        $requestToken = Request::create(env('APP_URL') . '/oauth/token', 'post');
        $response = Route::dispatch($requestToken);

        return $response;
    }

    /**
     * ログアウト処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->noContent();
    }
}
