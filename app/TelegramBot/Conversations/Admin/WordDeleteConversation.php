<?php
namespace App\TelegramBot\Conversations\Admin;

use App\Models\Word;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup as KeyboardInlineKeyboardMarkup;

class WordDeleteConversation extends Conversation
{
    protected $wordId;

    public function start(Nutgram $bot)
    {
        $bot->sendMessage('O\'chirish uchun so\'zni kiriting:');
        $this->next('searchWord');
    }

    public function searchWord(Nutgram $bot)
    {
        $searchQuery = $bot->message()->text;

        // Bu yerda qidiruv funksiyasini amalga oshiring va natijalarni oling
        // Misol uchun, bu sizning so'zlaringiz ma'lumotlar bazasida saqlangan bo'lsa:
        $words = Word::where('word', 'LIKE', "%{$searchQuery}%")->get();

        if ($words->isEmpty()) {
            $bot->sendMessage('Hech qanday so\'z topilmadi. Qaytadan urinib ko\'ring.');
            $this->start($bot);
            return;
        }

        $keyboard = [];
        foreach ($words as $word) {
            $keyboard[] =  InlineKeyboardButton::make(text: $word->name, callback_data: 'delete_' . $word->id);
        }

        $keyboard[] = [
           InlineKeyboardButton::make(text: 'Orqaga', callback_data: 'back')
        ];

        $replyMarkup = new KeyboardInlineKeyboardMarkup($keyboard);
        $bot->sendMessage('So\'zni tanlang:', reply_markup: $replyMarkup);

        $this->next('confirmDeletion');
    }

    public function confirmDeletion(Nutgram $bot)
    {
        $callbackData = $bot->callbackQuery()->data;

        if ($callbackData === 'back') {
            $this->start($bot);
            return;
        }

        if (strpos($callbackData, 'delete_') === 0) {
            $this->wordId = str_replace('delete_', '', $callbackData);
            $bot->sendMessage('Ishonchingiz komilmi?',
                     reply_markup: KeyboardInlineKeyboardMarkup::make()
                     ->addRow(
                         InlineKeyboardButton::make("Ha", callback_data: 'confirm_delete'),
                         InlineKeyboardButton::make("Yo\'q", callback_data:  'back')
                     )
            );
            $this->next('confirmDelete');
        }
    }

    public function confirmDelete(Nutgram $bot)
    {
        $callbackData = $bot->callbackQuery()->data;

        if ($callbackData === 'confirm_delete') {
            // So'zni o'chirish jarayoni
            Word::destroy($this->wordId);
            $bot->sendMessage('So\'z muvaffaqiyatli o\'chirildi.');
            $this->start($bot);
        } else {
            $this->start($bot);
        }
    }
}
