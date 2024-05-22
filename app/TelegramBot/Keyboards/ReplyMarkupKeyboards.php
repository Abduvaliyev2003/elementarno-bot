<?php

namespace App\TelegramBot\Keyboards;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class ReplyMarkupKeyboards
{
    protected static $menus  = [
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
            'uz' => '⬅️ Orqaga',
            'en' => '⬅️ Back',
            'ru' => '⬅️ Назад'
        ],
        [
            'uz' => '⚙️ Sozlamalar',
            'en' => '⚙️ Settings',
            'ru' => '⚙️ Настройки'
        ],
    ];
    protected static $settingMenu  = [
        [
            'uz' => '⬅️ Orqaga',
            'ru' => '⬅️ Назад'
        ],
        [
            'uz' => '👤 Ismni o‘zgartirish',
            'ru' => '👤 Ismni o‘zgartirish'
        ],
        [
            'uz' => '🏙 Shaharni o‘zgartirish',
            'ru' => '🏙 Shaharni o‘zgartirish'
        ],
        [
            'uz' => '🇺🇿🇷🇺 Tilni o‘zgartirish',
            'ru' => '🇺🇺🇿🇷🇺 Изменить язык'
        ],
        [
            'uz' => '🚪 Chiqish',
            'ru' => '🚪 Chiqish'
        ],
    ];
    public static function language(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(
            resize_keyboard: true,
            one_time_keyboard: true,
        )->addRow(
            KeyboardButton::make("🇺🇿O'zbekcha"),
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
                text: $text,
                request_contact: true
            )
        );
    }

    public static function menu(): ReplyKeyboardMarkup
    {
        return self::createKeyboard(self::$menus);
    }

    public static function setting($bot)
    {
         $bot->sendMessage(
            text: 'Harakatni tanlang:',
            reply_markup: self::createKeyboard(self::$settingMenu)
        );
    }

    private static function createKeyboard(array $menusData): ReplyKeyboardMarkup
    {
        $replyKeyboardMarkup = ReplyKeyboardMarkup::make(
            resize_keyboard: true,
            one_time_keyboard: true
        );

        $menus = [];
        foreach ($menusData as $menu) {
            $menus[] = KeyboardButton::make(
                text: $menu['uz']
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

}
