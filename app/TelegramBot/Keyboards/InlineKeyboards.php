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
            InlineKeyboardButton::make("Kartalar", callback_data: 'admin:add_card'),
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


    public static function categoryKey()
    {
        $inlineKeyboardMarkup = InlineKeyboardMarkup::make();
        $categories = [];

        foreach (WordCategories::get() as $category) {
            $regions[] = InlineKeyboardButton::make(
                text: $category->title_uz, callback_data: 'cate' . $category->id
            );
            if (count($regions) == 2) {
                $inlineKeyboardMarkup->addRow(...$categories);
                $categories = [];
            }
        }

        if (count($categories)) {
            $inlineKeyboardMarkup->addRow(...$categories);
        }

        return $inlineKeyboardMarkup;
    }
}
