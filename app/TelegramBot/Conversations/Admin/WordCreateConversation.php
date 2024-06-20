<?php

namespace App\TelegramBot\Conversations\Admin;

use App\Models\Word;
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
        $bot->sendMessage("So`zni rasmini jonating");
        $this->next('sixthStep');
    }

    public function sixthStep(Nutgram $bot)
    {
        // Save the image file ID (if available)
        $this->image = $bot->message()->photo[0]->file_id ?? null;

        // Request audio or URL for pronunciation
        $bot->sendMessage("So`zni o`qlish ovazni yoki URL jonating");
        $this->next('seventhStep');
    }

    public function seventhStep(Nutgram $bot)
    {
        // Save the audio file ID or URL for pronunciation
        $this->audio = $bot->message()->voice->file_id ?? $bot->message()->text;

        // Process and store collected data
        $this->processAndStoreData($bot);
    }

    private function processAndStoreData(Nutgram $bot)
    {
        // Validate and process image if available
        if ($this->image) {
            $photoFile = $bot->getFile($this->image);
            $photoUrl = $bot->getDownloadUrl($photoFile);
            $photoContent = file_get_contents($photoUrl);

            if ($photoContent) {
                // Store the photo using Laravel Storage
                $photoFileName = uniqid('photo_', true) . '.jpg'; // Generate unique filename
                Storage::disk('telegram')->put($photoFileName, $photoContent);
                $this->image = $photoFileName;
            } else {
                // Handle photo download error (optional)
                $bot->sendMessage("Rasm yuklab olinmadi. Iltimos qayta urinib ko`ring.");
                return;
            }
        }

        if ($this->audio) {

            if (filter_var($this->audio, FILTER_VALIDATE_URL)) {
                
                $audioContent = file_get_contents($this->audio);
                if ($audioContent) {
                    $audioFileName = uniqid('audio_', true) . '.ogg';
                    Storage::disk('telegram')->put($audioFileName, $audioContent);
                    $this->audio = $audioFileName;
                } else {
                    $bot->sendMessage("Ovoz URL yuklab olinmadi. Iltimos qayta urinib ko`ring.");
                    return;
                }
            } else {
                $audioFile = $bot->getFile($this->audio);
                $audioUrl = $bot->getDownloadUrl($audioFile);
                $audioContent = file_get_contents($audioUrl);

                if ($audioContent) {
                    $audioFileName = uniqid('audio_', true) . '.ogg';
                    Storage::disk('telegram')->put($audioFileName, $audioContent);
                    $this->audio = $audioFileName;
                } else {
                    $bot->sendMessage("Ovoz yuklab olinmadi. Iltimos qayta urinib ko`ring.");
                    return;
                }
            }
        }

        // Save the collected data to the database
        Word::create([
            'name' => $this->name,
            'pronunciation' => $this->pronunciation,
            'translations' => $this->translations,
            'image' => $this->image,
            'audio' => $this->audio,
        ]);

        // Inform the user that the word has been successfully created
        $bot->sendMessage("So`z muvaffaqiyatli yaratildi.");
        $this->end();
    }
}
