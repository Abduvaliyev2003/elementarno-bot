<?php

namespace App\TelegramBot\Keyboards;

use App\Models\Regions;
use App\Models\WordCategories;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class InlineKeyboards
{
    public static function address(string $reg):InlineKeyboardMarkup
    {
        $inlineKeyboardMarkup = InlineKeyboardMarkup::make();
        $regions = [];

        foreach (Regions::get() as $region) {
            $regions[] = InlineKeyboardButton::make(
                text: $region->name, callback_data: $reg . $region->id
            );
            if (count($regions) == 2) {
                $inlineKeyboardMarkup->addRow(...$regions);
                $regions = [];
            }
        }

        if (count($regions)) {
            $inlineKeyboardMarkup->addRow(...$regions);
        }

        return $inlineKeyboardMarkup;
    }


    public static function language()
    {
        return InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make("🇺🇿O'zbekcha", callback_data: 'lang: uz'),
            InlineKeyboardButton::make("🇷🇺Русский", callback_data: 'lang: ru')
        );

    }

    public static function adminMenu()
    {
        return InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make("Kartalar", callback_data: 'admin:cards'),
            InlineKeyboardButton::make("Category", callback_data: 'admin:categories')
        );
    }
    public static function CategoryMenu()
    {
        return InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make("Delete", callback_data: 'category:delete'),
            InlineKeyboardButton::make("Update", callback_data: 'category:update'),
            InlineKeyboardButton::make("Add Category", callback_data: 'category:add')
        );
    }

    public static function wordMenu()
    {
        return InlineKeyboardMarkup::make()
        ->addRow(
            InlineKeyboardButton::make("Delete", callback_data: 'word:delete'),
            InlineKeyboardButton::make("Update", callback_data: 'word:update'),
            InlineKeyboardButton::make("Add Word", callback_data: 'word:add')
        );
    }

    public static function categoryKey($call  = 'cate')
    {
        $inlineKeyboardMarkup = InlineKeyboardMarkup::make();
        $categories = [];

        foreach (WordCategories::get() as $category) {
            $categories[] = InlineKeyboardButton::make(
                text: $category->title_uz, callback_data: $call . $category->id
            );
            if (count($categories) == 2) {
                $inlineKeyboardMarkup->addRow(...$categories);
                $categories = [];
            }
        }

        if (count($categories)) {
            $inlineKeyboardMarkup->addRow(...$categories);
        }

        return $inlineKeyboardMarkup;
    }


    public static function category($bot)
    {
        $inlineKeyboardMarkup = InlineKeyboardMarkup::make();
        $categories = [];

        foreach (WordCategories::get() as $category) {
            $categories[] = InlineKeyboardButton::make(
                text: $category->title_uz, callback_data: $category->id
            );
            if (count($categories) == 2) {
                $inlineKeyboardMarkup->addRow(...$categories);
                $categories = [];
            }
        }

        if (count($categories)) {
            $inlineKeyboardMarkup->addRow(...$categories);
        }


        $bot->sendMessage('Soz turini tanlang:', reply_markup: $inlineKeyboardMarkup);

    }
}
