<?php

namespace App\TelegramBot\Commands;

use App\Models\User;
use App\TelegramBot\Admin\AdminMenu;
use App\TelegramBot\Conversations\MenuConversation;
use App\TelegramBot\Conversations\RegisterConversation;
use App\TelegramBot\Keyboards\InlineKeyboards;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
define('ADMIN_CHAT_ID',97386695542);
class StartCommand extends Command
{
    protected string $command  = 'start';

    protected ?string $description = 'start command';

    public function handle(Nutgram $bot): void
    {
        if(User::where('telegram_id', $bot->chatId())->first()){
            $chatId = $bot->chat()->id;
            if ($chatId == ADMIN_CHAT_ID) {
                $bot->sendMessage(
                    text: 'Welcome, Admin!',
                    reply_markup: InlineKeyboards::adminMenu()
                );
            } else {
                $first_name = $bot->user()->first_name;
                MenuConversation::begin( bot: $bot,
                userId: $bot->userId(),
                chatId: $bot->chatId(),);
            }

        } else  {
            RegisterConversation::begin(
                bot: $bot,
                userId: $bot->userId(),
                chatId: $bot->chatId(),
            );
        }

    }

}

