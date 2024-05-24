<?php


namespace  App\TelegramBot\LookUserPage;

use App\Repositories\UserRepository;
use App\TelegramBot\Conversations\MenuConversation;
use SergiX44\Nutgram\Nutgram;

class Back
{
    public  static function backPage(Nutgram $bot)
    {
        $page = UserRepository::userPage($bot->chat()->id);
        switch ($page->type) {
            case 'setting':
                MenuConversation::begin($bot);
                break;
            case '2':
                // do something
                break;
            case '3':
                // do something
                break;
        }
    }
}
