<?php

namespace App\TelegramBot\Conversations\Admin;

use App\Models\Word;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\Update;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class WordUpdateConversation extends Conversation
{
    protected $selectedWordId;
    protected $selectedWord;
    protected $currentPage = 1;
    protected $wordsPerPage = 3;

    public function start(Nutgram $bot)
    {
        $this->askForPage($bot);
    }

    public function askForPage(Nutgram $bot, $update = false)
{
    // Paginate words
    $words = Word::paginate($this->wordsPerPage, ['*'], 'page', $this->currentPage);

    // Check if the paginated list is empty
    if ($words->isEmpty()) {
        $bot->sendMessage("So`zlar ro`yxati bo`sh.");
        $this->end();
        return;
    }

    // Prepare the message with the current page and list of words
    $listMessage = "So`zlar ro`yxati sahifasi: {$words->currentPage()} / {$words->lastPage()}\n\n";
    foreach ($words as $word) {
        $listMessage .= "/{$word->id}: {$word->name}\n";
    }

    // Prepare the inline keyboard for pagination
    $keyboard = [];

    // Add "Previous page" button if not on the first page
    if ($words->currentPage() > 1) {
        $keyboard[] = InlineKeyboardButton::make(text:'Oldingi sahifa', callback_data: 'prev_page');
    }

    // Add "Next page" button if there are more pages
    if ($words->hasMorePages()) {
        $keyboard[] = InlineKeyboardButton::make(text: 'Keyingi sahifa', callback_data: 'next_page');
    }

    // Create InlineKeyboardMarkup with the constructed keyboard
    $replyMarkup = InlineKeyboardMarkup::make();
    $replyMarkup->addRow(...$keyboard);

    // Send the message with HTML parse mode and inline keyboard
    if($update){
        $bot->editMessageText($listMessage, reply_markup: $replyMarkup);

    } else  {
        $bot->sendMessage($listMessage, reply_markup: $replyMarkup);
    }

    $this->next('waitForSelection');
}


    public function waitForSelection(Nutgram $bot)
    {
        $callbackData = $bot->callbackQuery()->data ?? null;
        $wordId = $bot->message()->text;
        if ($callbackData === 'next_page') {
            $this->currentPage++;
            $this->askForPage($bot, true);
            return;
        } elseif ($callbackData === 'prev_page') {
            $this->currentPage--;
            if ($this->currentPage < 1) {
                $this->currentPage = 1;
            }
            $this->askForPage($bot, true);
            return;
        }
        $wordid  =  str_replace('/', '', $wordId);
        if (is_numeric($wordid)) {
            $this->selectedWordId = $wordid;
            $this->selectedWord = Word::where('id',($this->selectedWordId))->first();

            if ($this->selectedWord) {
                $this->askForName($bot);
            } else {
                $bot->sendMessage("So`z topilmadi. Iltimos qayta urinib ko`ring.");
                $this->askForPage($bot);
            }
        }
    }

    public function askForName(Nutgram $bot)
    {
        $bot->sendMessage("Tanlangan so'zning yangi nomini kiriting yoki 'Keyingisi' tugmasini bosing:", reply_markup: ReplyMarkupKeyboards::getNavigationKeyboard());
        $this->next('updateName');
    }

    public function updateName(Nutgram $bot)
    {
        if ($bot->message()->text !== '⏩ Keyingisi') {
            $this->selectedWord->name = $bot->message()->text;
        }
        $bot->sendMessage("So'zning yangi talaffuzini kiriting yoki 'Keyingisi' tugmasini bosing:", reply_markup: ReplyMarkupKeyboards::getNavigationKeyboard());
        $this->next('updatePronunciation');
    }

    public function updatePronunciation(Nutgram $bot)
    {
        if ($bot->message()->text !== '⏩ Keyingisi') {
            $this->selectedWord->pronunciation = $bot->message()->text;
        }
        $bot->sendMessage("So'zning yangi rus tilidagi tarjimasini kiriting yoki 'Keyingisi' tugmasini bosing:", reply_markup: ReplyMarkupKeyboards::getNavigationKeyboard());
        $this->next('updateRuTranslation');
    }

    public function updateRuTranslation(Nutgram $bot)
    {
        if ($bot->message()->text !== '⏩ Keyingisi') {
            $translations = $this->selectedWord['translations'];
            $translations['ru'] = $bot->message()->text;
            $this->selectedWord['translations'] = $translations;
        }
        $bot->sendMessage("So'zning yangi o'zbek tilidagi tarjimasini kiriting yoki 'Keyingisi' tugmasini bosing:", reply_markup: ReplyMarkupKeyboards::getNavigationKeyboard());
        $this->next('updateUzTranslation');
    }

    public function updateUzTranslation(Nutgram $bot)
    {
        if ($bot->message()->text !== '⏩ Keyingisi') {
            $translations = $this->selectedWord['translations'];
            $translations['uz'] = $bot->message()->text;
            $this->selectedWord['translations'] = $translations;
        }
        $bot->sendMessage("So'zning yangi rasmini yuboring (agar o'zgartirilishi kerak bo'lsa) yoki 'Keyingisi' tugmasini bosing:", reply_markup:ReplyMarkupKeyboards::getNavigationKeyboard());
        $this->next('updateImage');
    }

    public function updateImage(Nutgram $bot)
    {
        if (!empty($bot->message()->photo)) {
            $this->selectedWord->image = $bot->message()->photo[0]->file_id;
        }
        $bot->sendMessage("So'zning yangi talaffuz ovozini yoki URL-ni yuboring (agar o'zgartirilishi kerak bo'lsa) yoki 'Keyingisi' tugmasini bosing:",  reply_markup: ReplyMarkupKeyboards::getNavigationKeyboard());
        $this->next('updateAudio');
    }

    public function updateAudio(Nutgram $bot)
    {
        if (!empty($bot->message()->voice)) {
            $this->selectedWord->audio = $bot->message()->voice->file_id;
        }
        $this->processAndUpdateData($bot);
    }

    private function processAndUpdateData(Nutgram $bot)
    {
        // Validate and process image if available
        if (is_numeric($this->selectedWord->image)   ) {
            $photoFile = $bot->getFile($this->selectedWord->image);
            $photoUrl = $bot->getDownloadUrl($photoFile);
            $photoContent = file_get_contents($photoUrl);

            if ($photoContent) {
                // Store the photo using Laravel Storage
                $photoFileName = uniqid('photo_', true) . '.jpg'; // Generate unique filename
                Storage::disk('public/words/')->put($photoFileName, $photoContent);
                $this->selectedWord->image = $photoFileName;
            } else {
                // Handle photo download error (optional)
                $bot->sendMessage("Rasm yuklab olinmadi. Iltimos qayta urinib ko`ring.");
                return;
            }
        }

        // Validate and process audio if available
        if (is_numeric($this->selectedWord->image)) {
            // Check if $this->selectedWord->audio is a file ID or a URL
            if (filter_var($this->selectedWord->audio, FILTER_VALIDATE_URL)) {
                // Handle URL case (optional)
                // Example: Fetch audio content from URL and store using Laravel Storage
                $audioContent = file_get_contents($this->selectedWord->audio);
                if ($audioContent) {
                    $audioFileName = 'word-audio/' . uniqid('audio_', true) . '.ogg';
                    Storage::disk('public')->put($audioFileName, $audioContent);
                    $this->selectedWord->audio = $audioFileName;
                } else {
                    // Handle audio URL download error (optional)
                    $bot->sendMessage("Ovoz URL yuklab olinmadi. Iltimos qayta urinib ko`ring.");
                    return;
                }
            } else {
                // Handle file ID case (already downloaded by Telegram)
                $audioFile = $bot->getFile($this->selectedWord->audio);
                $audioUrl = $bot->getDownloadUrl($audioFile);
                $audioContent = file_get_contents($audioUrl);

                if ($audioContent) {
                    $audioFileName = 'word-audio/' . uniqid('audio_', true) . '.ogg';
                    Storage::disk('public')->put($audioFileName, $audioContent);
                    $this->selectedWord->audio = $audioFileName;
                } else {
                    // Handle audio file ID download error (optional)
                    $bot->sendMessage("Ovoz yuklab olinmadi. Iltimos qayta urinib ko`ring.");
                    return;
                }
            }
        }

        // Save updated word data to the database
        $this->selectedWord->save();

        // Inform the user that the word has been successfully updated
        $bot->sendMessage("So`z muvaffaqiyatli yangilandi.");
        $this->end();
    }
}
