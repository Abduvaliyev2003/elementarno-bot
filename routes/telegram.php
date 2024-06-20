<?php

/** @var SergiX44\Nutgram\Nutgram $bot */


use App\Models\User;
use App\Repositories\UserRepository;
use App\TelegramBot\Actions\SetAddress;
use App\TelegramBot\Actions\SetLanguage;
use App\TelegramBot\Actions\SetUserPage;
use App\TelegramBot\Admin\Category\CategoryAdmin;
use App\TelegramBot\Admin\Words\WordAdmin;
use App\TelegramBot\Commands\StartCommand;
use App\TelegramBot\Conversations\Admin\CategoryCreateConversation;
use App\TelegramBot\Conversations\ChangeNameConversation;
use App\TelegramBot\Conversations\RegisterConversation;
use App\TelegramBot\Keyboards\InlineKeyboards;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use App\TelegramBot\LookUserPage\Back;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeAllGroupChats;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeAllPrivateChats;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

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


$bot->onText('Biz haqimizda', function(Nutgram $bot){
    $bot->sendMessage(
        text: 'https://telegra.ph/Elementarno-05-23',
        reply_markup: InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make('Open', web_app: WebAppInfo::make('https://www.elementarnoo.uz/'))
            )
    );

});

$bot->onText('Kartichkalar', function(Nutgram $bot){
    $bot->sendMessage(
        text: 'https://telegra.ph/Elementarno-05-23',
        reply_markup: InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make('Open', web_app: WebAppInfo::make('https://666ed8f43d4ad0c9edaa6916--capable-sopapillas-c7ca1a.netlify.app/'))
            )
    );
});
$bot->onText('⚙️ Sozlamalar', function (Nutgram $bot) {
    SetUserPage::set($bot->chat()->id, 'setting');
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

$bot->onText('⬅️ Orqaga', function (Nutgram $bot) {
    Back::backPage($bot);
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


$bot->onCallbackQueryData('admin:categories', CategoryAdmin::class);
$bot->onCallbackQueryData('admin:cards', WordAdmin::class);
$bot->onCallbackQueryData('category:{param}', function (Nutgram $bot, $param){
    CategoryAdmin::filterCall($bot, $param);
});
$bot->onCallbackQueryData('word:{param}', function (Nutgram $bot, $param){
    WordAdmin::filterCall($bot, $param);
});
