<?php

namespace App\TelegramBot\Commands;

use App\Models\User;
use App\TelegramBot\Conversations\MenuConversation;
use App\TelegramBot\Conversations\RegisterConversation;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class StartCommand extends Command
{
    protected string $command  = 'start';

    protected ?string $description = 'start command';

    public function handle(Nutgram $bot): void
    {
        if(User::where('telegram_id', $bot->chatId())->first()){
            $first_name = $bot->user()->first_name;
            MenuConversation::begin( bot: $bot,
            userId: $bot->userId(),
            chatId: $bot->chatId(),);
        } else  {
            RegisterConversation ::begin(
                bot: $bot,
                userId: $bot->userId(),
                chatId: $bot->chatId(),
            );
        }

    }

}

