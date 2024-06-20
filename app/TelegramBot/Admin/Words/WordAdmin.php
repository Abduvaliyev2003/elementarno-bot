<?php


namespace App\TelegramBot\Admin\Words;

use App\Models\WordCategories;
use App\TelegramBot\Conversations\Admin\CategoryCreateConversation;
use App\TelegramBot\Conversations\Admin\WordCreateConversation;
use App\TelegramBot\Conversations\Admin\WordUpdateConversation;
use App\TelegramBot\Keyboards\InlineKeyboards;
use SergiX44\Nutgram\Nutgram;

class WordAdmin
{
    public function __invoke(Nutgram $bot)
    {
        $bot->deleteMessage($bot->chatId(), $bot->messageId());
        $categoryListText = "Kartichkalara";
        $bot->sendMessage(
            text: $categoryListText,
            reply_markup: InlineKeyboards::wordMenu()
        );
    }

    public static function filterCall(Nutgram $bot, $param)
    {
        $bot->deleteMessage($bot->chatId(), $bot->messageId());
        if($param == 'add')
        {
            WordCreateConversation::begin($bot);
        } elseif ($param == 'update')
        {
            WordUpdateConversation::begin($bot);
        }
    }
}
