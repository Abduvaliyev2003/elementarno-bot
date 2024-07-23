<?php

namespace App\TelegramBot\Keyboards;

use App\Models\User;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class ReplyMarkupKeyboards
{
    protected static $menus = [
        [
            'uz' => 'Kartichkalar',
            'en' => 'card',
            'ru' => 'Карты'
        ],
        [
            'uz' => 'Biz haqimizda',
            'en' => 'us',
            'ru' => 'о нас'
        ],
        [
            'uz' => 'Test',
            'en' => 'test',
            'ru' => 'Тест'
        ],
        [
            'uz' => 'Buyurtma berish',
            'en' => 'order',
            'ru' => 'Разместить заказ'
        ],
        [
            'uz' => '⚙️ Sozlamalar',
            'en' => '⚙️ Settings',
            'ru' => '⚙️ Настройки'
        ],
    ];

    protected static $settingMenu = [
        [
            'uz' => '⬅️ Orqaga',
            'ru' => '⬅️ Назад'
        ],
        [
            'uz' => '👤 Ismni o‘zgartirish',
            'ru' => '👤 Изменить имя'
        ],
        [
            'uz' => '🏙 Shaharni o‘zgartirish',
            'ru' => '🏙 Изменить город'
        ],
        [
            'uz' => '🇺🇿🇷🇺 Tilni o‘zgartirish',
            'ru' => '🇺🇿🇷🇺 Изменить язык'
        ],
        [
            'uz' => '🚪 Chiqish',
            'ru' => '🚪 Выйти'
        ],
    ];

    public static function language(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(
            resize_keyboard: true,
            one_time_keyboard: true,
        )->addRow(
            KeyboardButton::make('🇺🇿O\'zbekcha'),
            KeyboardButton::make('🇷🇺Русский'),
        );
    }

    public static function phone(?string $text = null): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(
            resize_keyboard: true,
            one_time_keyboard: true
        )->addRow(
            KeyboardButton::make(
                text: $text ?? __('telegram.buttons.send_contact'),
                request_contact: true
            )
        );
    }

    public static function menu(Nutgram $bot): ReplyKeyboardMarkup
    {
        return self::createKeyboard(self::$menus, $bot);
    }

    public static function setting($bot)
    {
        $telegramId = $bot->chatId();
        $language = User::findByTelegramId($telegramId);

        $text = $language == 'ru' ? 'Выберите действие' : 'Harakatni tanlang';
        $bot->sendMessage(
            text: $text,
            reply_markup: self::createKeyboard(self::$settingMenu, $bot)
        );

    }

    private static function createKeyboard(array $menusData, $bot): ReplyKeyboardMarkup
    {
        $telegramId = $bot->chatId();
        $language = User::findByTelegramId($telegramId);
        $replyKeyboardMarkup = ReplyKeyboardMarkup::make(
            resize_keyboard: true,
            one_time_keyboard: true
        );

        $menus = [];
        foreach ($menusData as $menu) {
            $menus[] = KeyboardButton::make(
                text: $language == 'ru' ? $menu['ru'] : $menu['uz']  // Rus tiliga moslashtrildi
            );
            if (count($menus) == 2) {
                $replyKeyboardMarkup->addRow(...$menus);
                $menus = [];
            }
        }

        if (count($menus)) {
            $replyKeyboardMarkup->addRow(...$menus);
        }

        return $replyKeyboardMarkup;
    }

     public static function getNavigationKeyboard()
    {
    return ReplyKeyboardMarkup::make(
        resize_keyboard: true,
        one_time_keyboard: true
    )->addRow(
        KeyboardButton::make(text: '⏩ Keyingisi'),
        KeyboardButton::make(text: '❌ Bekor qilish')
    );
    }
}
