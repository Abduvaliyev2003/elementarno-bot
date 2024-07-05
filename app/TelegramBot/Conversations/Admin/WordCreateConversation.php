<?php

namespace App\TelegramBot\Conversations\Admin;

use App\Models\Word;
use App\Models\WordCategories;
use App\TelegramBot\Admin\Words\WordAdmin;
use App\TelegramBot\Keyboards\InlineKeyboards;
use Exception;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class WordCreateConversation extends Conversation
{
    protected $name;
    protected $pronunciation;
    protected $translations = [];
    protected $image;
    protected $audio;
    protected $categoryId;

    public function start(Nutgram $bot)
    {
        $bot->sendMessage("Yangi so`zni kirgazing");
        $this->next('secondStep');
    }

    public function secondStep(Nutgram $bot)
    {
        $this->name = $bot->message()->text;
        $bot->sendMessage("So`zni o`qlishni kirgazing: ю | [ ju: ]");
        $this->next('thirdStep');
    }

    public function thirdStep(Nutgram $bot)
    {
        $this->pronunciation = $bot->message()->text;
        $bot->sendMessage("So`zni rus tilida tarjimasni: вы, вами, вас, вам");
        $this->next('fourthStep');
    }

    public function fourthStep(Nutgram $bot)
    {
        $this->translations['ru'] = $bot->message()->text;
        $bot->sendMessage("So`zni o`zbek tilida tarjimasni: siz, sizga, sizni");
        $this->next('fifthStep');
    }



    public function fifthStep(Nutgram $bot)
    {
        $this->translations['uz'] = $bot->message()->text;
        InlineKeyboards::category($bot);
        $this->next('categoryStep');
    }

    public function categoryStep(Nutgram $bot)
    {
        $this->categoryId = $bot->callbackQuery()->data;

        $bot->sendMessage("So`zni rasmini jonating");
        $this->next('uploadImageStep');
    }

    public function uploadImageStep(Nutgram $bot)
    {
        // Save the image file ID (if available)
        $this->image = $bot->message()->photo[0]->file_id ??  $bot->message()->document->file_id ?? null;

        if ($this->image) {
            // Process image
            $this->processAndStoreImage($bot);
        } else {
            $bot->sendMessage("Rasm jonatilmadi. Iltimos qayta urinib ko`ring.");
            return;
        }

        // Request audio or URL for pronunciation
        $bot->sendMessage("So`zni o`qlish ovazni yoki URL jonating");
        $this->next('uploadAudioStep');
    }

    public function uploadAudioStep(Nutgram $bot)
    {
        // Save the audio file ID or URL for pronunciation
        $this->audio = $bot->message()->voice->file_id ?? $bot->message()->text;

        if ($this->audio) {
            // Process audio
            $this->processAndStoreAudio($bot);
        } else {
            $bot->sendMessage("Ovoz yoki URL jonatilmadi. Iltimos qayta urinib ko`ring.");
            return;
        }

        // Process and store collected data
        $this->processAndStoreData($bot);
    }

    private function processAndStoreImage(Nutgram $bot)
    {
        $photoFile = $bot->getFile($this->image);
        $filePath = $photoFile->file_path;
        $token = env('TELEGRAM_TOKEN');

        // Create the URL to download the file
        $photoUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";

        // Download the file content
        $photoContent = file_get_contents($photoUrl);

        if ($photoContent) {
            // Generate unique filename
            $photoFileName = uniqid('photo_', true) . '.jpg';

            // Define the path within the public disk
            $storagePath = 'words/' . $photoFileName;

            // Save the file to the public disk
            Storage::disk('public')->put($storagePath, $photoContent);

            // Update $this->image with the new filename
            $this->image = $storagePath;

            // Update $this->image with the new filename
            return $storagePath;
        } else {
            throw new Exception("Rasm yuklab olinmadi. Iltimos qayta urinib ko'ring.");
        }
    }

    private function processAndStoreAudio(Nutgram $bot)
    {
        if( $bot->message()->text == '' || $bot->message()->text == null ){
        if (filter_var($this->audio, FILTER_VALIDATE_URL)) {
            // Handle audio URL directly by storing the URL itself
            $audioFileName = $this->audio;

            $audioContent = file_get_contents($this->audio);
            if ($audioContent) {
                $audioFileName = 'word-audio/' . uniqid('audio_', true) . '.ogg';
                Storage::disk('public')->put($audioFileName, $audioContent);
                $this->audio = $audioFileName;
            } else {
                $bot->sendMessage("Ovoz URL yuklab olinmadi. Iltimos qayta urinib ko`ring.");
            }
        } else {
            $audioFile = $bot->getFile($this->audio);

            // Assuming the `getFile` method returns a file ID or file object with a `file_path` property
            $audioUrl = 'https://api.telegram.org/file/bot' . env('TELEGRAM_TOKEN') . '/' . $audioFile->file_path;

            $audioContent = file_get_contents($audioUrl);

            if ($audioContent) {
                $audioFileName = 'word-audio/' . uniqid('audio_', true) . '.ogg';
                Storage::disk('public')->put($audioFileName, $audioContent);
                $this->audio = $audioFileName;
            } else {
                $bot->sendMessage("Ovoz yuklab olinmadi. Iltimos qayta urinib ko`ring.");
            }
        }
       } else {
           if(filter_var($this->audio, FILTER_VALIDATE_URL)) {
               $this->audio = $bot->message()->text;
           }
       }
    }

    private function processAndStoreData(Nutgram $bot)
    {
        // Save the collected data to the database
        Word::create([
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'pronunciation' => $this->pronunciation,
            'translations' => $this->translations,
            'image' => $this->image,
            'audio' => $this->audio,
        ]);

        // Inform the user that the word has been successfully created
        $bot->sendMessage("So`z muvaffaqiyatli yaratildi.");
        $wordAdmin = new WordAdmin();
        $wordAdmin($bot);
        $this->end();
    }




}


