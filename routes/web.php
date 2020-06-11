<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions', 'QuestionsController')->except('show');
Route::post('/questions/{question}/answers', 'AnswersController@store')->name('answers.store');
Route::get('/questions/{question}/answers/{answer}/edit', 'AnswersController@edit')->name('answers.edit');
Route::patch('/questions/{question}/answers/{answer}', 'AnswersController@update')->name('answers.update');
Route::delete('/questions/{question}/answers/{answer}', 'AnswersController@destroy')->name('answers.destroy');
Route::get('/questions/{slug}', 'QuestionsController@show')->name('questions.show');
