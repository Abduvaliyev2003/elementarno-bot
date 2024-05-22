<?php

namespace App\TelegramBot\Keyboards;

use App\Models\Regions;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class InlineKeyboards
{
    public static function address():InlineKeyboardMarkup
    {
        $inlineKeyboardMarkup = InlineKeyboardMarkup::make();
        $regions = [];

        foreach (Regions::get() as $region) {
            $regions[] = InlineKeyboardButton::make(
                text: $region->name, callback_data: 'region_' . $region->id
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
}
