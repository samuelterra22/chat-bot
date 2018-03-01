<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    $botman->hears('foo', function (BotMan $bot) {
        // get user info
        $bot->reply(json_encode($bot->getUser()->getInfo()));
        $bot->reply('Hello World');
    });

    // Get boleto
    $botman->hears('boleto', function (BotMan $bot) {
        $att = new File('https://www.boletobancario.com/boletofacil/img/boleto-facil-exemplo.pdf');
        $message = OutgoingMessage::create('')->withAttachment($att);
        $bot->reply($message);
    });

    // ask
    $botman->hears('meu nome é {name} e sou de {place}', function (BotMan $bot, $name, $place) {
        $bot->reply("Tudo bem {$name}? Como está o clima em {$place}?");
    });

    // listen for messages
    $botman->listen();

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
