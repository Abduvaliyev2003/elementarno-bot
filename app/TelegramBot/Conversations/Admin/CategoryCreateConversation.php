<?php

namespace App\TelegramBot\Conversations\Admin;

use App\Models\WordCategories;
use App\TelegramBot\Actions\SetName;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;


class  CategoryCreateConversation extends Conversation
{
    public array $lang = [];
    public function start(Nutgram $bot):void
    {
        $bot->sendMessage(
            text: "Title yozin O`zbek tilida"
        );
       $this->next('secondStep');
    }

    public function secondStep(Nutgram $bot):void
    {
        $this->lang['lang_uz'] = $bot->message()->text;
        $bot->sendMessage(
            text: "Title yozin Rus tilida"
        );
       $this->next('thirdStep');
    }

    public function thirdStep(Nutgram $bot):void
    {
        $this->lang['lang_ru'] = $bot->message()->text;
        $bot->sendMessage(
            text: "rasm jonating"
        );
       $this->next('FourStep');
    }
    public function FourStep(Nutgram $bot)
{
    $message = $bot->message();
    
    // Check if the message contains a document, photo, etc.
    if (isset($message->document)) {
        $fileId = $message->document->file_id;
    } elseif (isset($message->photo)) {
        $fileId = end($message->photo)->file_id;
    } else {
        $bot->sendMessage("No file received.");
        return;
    }

    $localFilePath = $this->downloadFileFromTelegram($bot, $fileId);
    $fileUrl = $this->createFileUrl($localFilePath);


    WordCategories::query()->create([
        'title_uz' => $this->lang['lang_uz'],
        'title_ru' => $this->lang['lang_ru'],
        'file_url' => $fileUrl,
    ]);

    $bot->sendMessage("Karta yaratildi: $fileUrl");
    $this->end();
}


    public function downloadFileFromTelegram(Nutgram $bot, $fileId): string
{
    // Get the file path from Telegram
    $file = $bot->getFile($fileId);
    $filePath = $file->file_path;
    $token = env('TELEGRAM_TOKEN');
    // Create the URL to download the file
    $fileUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";

    // Define the local path to save the file
    $localFilePath = storage_path('app/public/' . basename($filePath));

    // Download and save the file locally
    file_put_contents($localFilePath, file_get_contents($fileUrl));

    return $localFilePath;
}

public function createFileUrl(string $localFilePath): string
{
    // Assuming your files are served from the 'public' disk
    return asset('storage/' . basename($localFilePath));
}

}
