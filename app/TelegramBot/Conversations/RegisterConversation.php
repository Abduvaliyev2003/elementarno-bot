<?php

namespace App\TelegramBot\Conversations;

use App\Models\Regions;
use App\Models\User;
use App\TelegramBot\Actions\SetLanguage;
use App\TelegramBot\Keyboards\InlineKeyboards;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use Illuminate\Support\Facades\App;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\MessageType;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class RegisterConversation extends Conversation
{
    public array $data = [];
    public array $region = [];
    public array $lang = [];

    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {
        $first_name = $bot->user()->first_name;
        $bot->sendMessage(
            text: 'Assalomu alaykum ' . $first_name,
            reply_markup: ReplyMarkupKeyboards::language()
        );
        $this->next('secondStep');
    }

    public function secondStep(Nutgram $bot): void
    {
        if ("🇺🇿O'zbekcha" == $bot->message()->text) {
            $this->lang['lang'] = 'uz';
        } else if ("🇷🇺Русский" == $bot->message()->text) {
            $this->lang['lang'] = 'ru';
        }

        // Foydalanuvchi tanlagan tilga ko'ra xabarlarni lokalizatsiya qilish
        App::setLocale($this->lang['lang']);

        $bot->sendMessage(
            text: __('telegram.start_message_new_user'),
            reply_markup: ReplyMarkupKeyboards::phone(__('telegram.buttons.send_contact'))
        );
        $this->next('secondThird');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function secondThird(Nutgram $bot): void
    {
        // app()->setLocale($this->lang['lang']);
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
        $bot->sendMessage(
            text: 'Removing keyboard...',
            reply_markup: ReplyKeyboardRemove::make(true),
        )?->delete();
        $bot->sendMessage(
            text: __('telegram.success_number'),
            reply_markup: InlineKeyboards::address('region_')
        );
        $this->next('setAddress');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setAddress(Nutgram $bot): void
    {
        $region = $bot->callbackQuery()->data;
        app()->setLocale($this->lang['lang']);
        $bot->deleteMessage($bot->chatId(), $bot->messageId());
        $this->region['region_id'] = str_replace('region_', '', $region);
        $bot->sendMessage(text: __('telegram.success_address'));
        $this->next('setName');
    }

    public function setName(Nutgram $bot): void
    {
        // app()->setLocale($this->lang['lang']);
        $patient = User::where('phone', $this->data['phone'])
            ->whereNull('telegram_id')->first();
        if ($patient) {
            $patient->update([
                'telegram_id' => $bot->chatId(),
                'lang' => $this->lang['lang']  // Tilni yangilash
            ]);
        } else {
            User::create([
                'telegram_id' => $bot->chatId(),
                'name' => $bot->message()->text,
                'phone' => $this->data['phone'],
                'address' => $this->region['region_id'],
                'lang' => $this->lang['lang'],
                'is_verified' => false
            ]);
        }
        $bot->sendMessage(text: __('telegram.success_end_register'));
        MenuConversation::begin($bot, $bot->userId(), $bot->chatId());
        $this->end();
    }
}
