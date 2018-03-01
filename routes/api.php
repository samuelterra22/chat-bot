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

    // Hello World
    $botman->hears('foo', function ($bot) {
        // get user info
        $bot->reply(json_encode($bot->getUser()->getInfo()));
        $bot->reply('Hello World');
    });

    // ask
    $botman->hears('meu nome é {name} e sou de {place}', function ($bot, $name, $place) {
        $bot->reply("Tudo bem {$name}? Como está o clima em {$place}?");
    });

    // listen for messages
    $botman->listen();

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
