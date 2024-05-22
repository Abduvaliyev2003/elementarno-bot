<?php

namespace App\TelegramBot\Conversations;

use App\TelegramBot\Actions\SetName;
use App\TelegramBot\Keyboards\ReplyMarkupKeyboards;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;


class  ChangeNameConversation extends Conversation
{

    public function start(Nutgram $bot):void
    {
        $bot->sendMessage(
            text:__('telegram.change_name')
        );
       $this->next('secondStep');
    }

    public function secondStep(Nutgram $bot)
    {
        SetName::set($bot->chat()->id,  $bot->message()->text);
        ReplyMarkupKeyboards::setting($bot);
        $this->end();
    }


}
