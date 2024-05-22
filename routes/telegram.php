<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\TelegramBot\Commands\StartCommand;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeAllGroupChats;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeAllPrivateChats;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->registerCommand(StartCommand::class)->scope([
    new BotCommandScopeAllPrivateChats,
    new BotCommandScopeAllGroupChats,
]);

;

$bot->onText('⚙️ Sozlamalar', function (Nutgram $bot) {
    $bot->sendMessage(
        text: 'Harakatni tanlang:',
        reply_markup: ReplyMarkupKeyboards::setting()
    );
});
