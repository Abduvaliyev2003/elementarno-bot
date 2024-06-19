<?php


namespace App\TelegramBot\Admin\Category;

use App\Models\WordCategories;
use App\TelegramBot\Keyboards\InlineKeyboards;
use SergiX44\Nutgram\Nutgram;

class CategoryAdmin
{
    public function __invoke(Nutgram $bot)
    {
        $bot->deleteMessage($bot->chatId(), $bot->messageId());
        $categoryListText = "Please select a category:\n\n";
        foreach (WordCategories::get() as $index => $category) {
            $categoryListText .= ($index + 1) . ". " . $category->title_uz . "\n";
        }

        $bot->sendMessage(
            text: $categoryListText,
            reply_markup: InlineKeyboards::CategoryMenu()
        );
    }
}
