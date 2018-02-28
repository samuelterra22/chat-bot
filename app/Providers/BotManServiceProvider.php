<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 27/02/18
 * Time: 22:35
 */

namespace ChatBot\Providers;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Container\LaravelContainer;
use BotMan\BotMan\Storages\Drivers\FileStorage;
use Illuminate\Support\ServiceProvider;

class BotManServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('botman', function ($app) {
            $storage = new FileStorage(storage_path('botman'));

            $botman = BotManFactory::create(config('botman', []), new LaravelCache(), $app->make('request'),
                $storage);

            $botman->setContainer(new LaravelContainer($this->app));

            return $botman;
        });
    }

}