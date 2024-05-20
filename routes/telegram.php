<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\TelegramBot\Commands\StartCommand;
use SergiX44\Nutgram\Nutgram;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->registerCommand(StartCommand::class);

$bot->onText('My name is {name}', function (Nutgram $bot, $name) {
    $bot->sendMessage("Hi {$name}");
});