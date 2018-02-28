<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('botman', function (Request $request) {
    return $request->get('hub_challenge');
});

Route::post('botman', function (Request $request) {
    // get botman instance
    $botman = resolve('botman');

    $botman->hears('foo', function ($bot) {
        $bot->reply('Hello World');
    });

    // listen for messages
    $botman->listen();

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
