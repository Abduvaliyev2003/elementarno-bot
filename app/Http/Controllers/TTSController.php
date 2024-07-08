<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Rpc\Context\AttributeContext\Request;

class TTSController extends Controller
{
    public function synthesizeSpeech(Request $request)
    {
        $word = 'hello world';

        $client = new TextToSpeechClient();

        $input = new SynthesisInput();
        $input->setText($word);
        $voice = new VoiceSelectionParams();
        $voice->setLanguageCode('en-US');
        $audioConfig = new AudioConfig();
        $audioConfig->setAudioEncoding(AudioEncoding::MP3);

        $response = $client->synthesizeSpeech($input, $voice, $audioConfig);
        $audioContent = $response->getAudioContent();

        return response($audioContent)
            ->header('Content-Type', 'audio/mpeg')
            ->header('Content-Disposition', 'attachment; filename="speech.mp3"');
    }
}
