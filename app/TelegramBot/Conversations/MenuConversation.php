<?php

namespace App\TelegramBot\Conversations;

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
        $bot->sendMessage(
            text:__('telegram.menu'),
            reply_markup: ReplyMarkupKeyboards::menu()
        );
        $this->end();
    }


}
