<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Models\User;
use App\Repositories\UserRepository;
use App\TelegramBot\Actions\SetAddress;
use App\TelegramBot\Actions\SetLanguage;
use App\TelegramBot\Commands\StartCommand;
use App\TelegramBot\Conversations\ChangeNameConversation;
use App\TelegramBot\Conversations\RegisterConversation;
use App\TelegramBot\Keyboards\InlineKeyboards;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use SergiX44\Nutgram\Conversations\Conversation;
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

Conversation::refreshOnDeserialize();

$bot->registerCommand(StartCommand::class)->description('The start command!')->scope([
    new BotCommandScopeAllPrivateChats,
    new BotCommandScopeAllGroupChats,
]);



$bot->onText('⚙️ Sozlamalar', function (Nutgram $bot) {
    ReplyMarkupKeyboards::setting($bot);
});
$bot->onText('🇺🇿🇷🇺 Tilni o‘zgartirish', function (Nutgram $bot) {
    $bot->sendMessage(text: __('telegram.select_lang'), reply_markup: InlineKeyboards::language());
});

$bot->onText('🏙 Shaharni o‘zgartirish', function (Nutgram $bot) {
    $bot->sendMessage(text: __('telegram.select_Address'), reply_markup: InlineKeyboards::address('region '));
});

$bot->onText('👤 Ismni o‘zgartirish', function (Nutgram $bot) {
    ChangeNameConversation::begin($bot);
});


$bot->onCallbackQueryData('lang: {param}', function (Nutgram $bot, $param) {
    SetLanguage::set($bot->chat()->id, $param);
    $bot->deleteMessage($bot->chatId(), $bot->messageId());
    ReplyMarkupKeyboards::setting($bot);
});
$bot->onCallbackQueryData('region {param}', function (Nutgram $bot, $param) {
    SetAddress::set($bot->chat()->id, $param);
    $bot->deleteMessage($bot->chatId(), $bot->messageId());
    ReplyMarkupKeyboards::setting($bot);
});



$bot->onText('🚪 Chiqish', function (Nutgram $bot) {
    (new UserRepository())->logout($bot->chat()->id);
    (new StartCommand())->handle($bot);
});
