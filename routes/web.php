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

Route::view('{any}', 'spa')->where('any', '.*');

Route::get('/', 'QuestionsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('questions', 'QuestionsController')->except('show');
Route::post('/questions/{question}/answers', 'AnswersController@store')->name('answers.store');
Route::get('/questions/{question}/answers', 'AnswersController@index')->name('answers.index');
Route::get('/questions/{question}/answers/{answer}/edit', 'AnswersController@edit')->name('answers.edit');
Route::patch('/questions/{question}/answers/{answer}', 'AnswersController@update')->name('answers.update');
Route::delete('/questions/{question}/answers/{answer}', 'AnswersController@destroy')->name('answers.destroy');
Route::get('/questions/{slug}', 'QuestionsController@show')->name('questions.show');
Route::post('/answers/{answer}/accept', 'AcceptAnswerController')->name('answers.accept');

Route::post('/questions/{question}/favorites', 'FavoritesController@store')->name('questions.favorite');
Route::delete('/questions/{question}/favorites', 'FavoritesController@destroy')->name('questions.unfavorite');
Route::post('/questions/{question}/vote', 'VoteQuestionController');
Route::post('/answers/{answer}/vote', 'VoteAnswerController');
