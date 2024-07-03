<?php

namespace App\TelegramBot\Conversations;

use App\Models\User;
use App\TelegramBot\Actions\SetUserPage;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class  MenuConversation extends Conversation
{

    public function start(Nutgram $bot):void
    {
        SetUserPage::set($bot->chat()->id, 'menu');
        $telegramId = $bot->chatId();
        $language = User::findByTelegramId($telegramId);
        app()->setLocale($language);
        $bot->sendMessage(
            text:__('telegram.menu'),
            reply_markup: ReplyMarkupKeyboards::menu($bot)
        );
        $this->end();
    }


}
