<?php

namespace App\TelegramBot\Commands;

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
        $first_name = $bot->user()->first_name;

        $bot->sendMessage(
            text: 'Assalomu alaykum ' . $first_name,
            reply_markup: ReplyKeyboardMarkup::make(
                resize_keyboard: true,
            )
                ->addRow(
                    KeyboardButton::make('Send phone number',request_contact: true)
                )
        );
    }

}

