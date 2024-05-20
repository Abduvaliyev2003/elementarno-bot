<?php

namespace App\TelegramBot\Conversations;


use App\Models\User;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\MessageType;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class RegisterConversation extends Conversation
{
    public array $data = [];

    /**
     * @throws InvalidArgumentException
     */

    public function start(Nutgram $bot): void
    {

        $first_name = $bot->user()->first_name;
        $bot->sendMessage(
            text: 'Assalomu alaykum ' . $first_name,
            reply_markup: ReplyKeyboardMarkup::make(
                resize_keyboard: true,
            )
                ->addRow(
                   KeyboardButton::make('🇺🇿 Uzbek'),
                   KeyboardButton::make('🇷🇺 Русский')
                )
        );

        $this->next('secondStep');
    }

    public function secondStep(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: __('telegram.start_message_new_user'),
            reply_markup: ReplyKeyboardMarkup::make(
                resize_keyboard: true,
                one_time_keyboard: true
            )
                ->addRow(
                    KeyboardButton::make(
                        text: __('telegram.buttons.send_contact'),
                        request_contact: true,
                    )
                )
        );
        $this->next('secondThird');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function secondThird(Nutgram $bot): void
    {
        if ($bot->message()->getType() != MessageType::CONTACT) {
            $bot->sendMessage(__('telegram.error_number'));
            $this->next('secondStep');
            return;
        }
        if (str_starts_with($bot->message()->contact->phone_number, '+')) {
            $this->data['phone'] = str_replace('+', '', $bot->message()->contact->phone_number);
        } else {
            $this->data['phone'] = $bot->message()->contact->phone_number;
        }
        $bot->sendMessage(__('telegram.success_number'));
        $this->next('setName');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setName(Nutgram $bot): void
    {
        $patient = User::where('phone', $this->data['phone'])
            ->whereNull('telegram_id')->first();
        if ($patient) {
            $patient->update([
                'telegram_id' => $bot->chatId()
            ]);
        } else {
            User::create([
                'telegram_id' => $bot->chatId(),
                'name' => $bot->message()->text,
                'phone' => $this->data['phone'],
                'address' => null,
                'is_verified' => false
            ]);
        }
        $bot->sendMessage(text: __('telegram.success_end_register'));
        $this->end();
    }
}
