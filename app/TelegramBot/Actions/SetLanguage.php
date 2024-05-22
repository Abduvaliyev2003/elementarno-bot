<?php
namespace App\TelegramBot\Actions;


use App\Repositories\UserRepository;

class SetLanguage
{
    public static function set(string $chat_id, string $lang): void
    {
        (new UserRepository())->setLanguage($chat_id, $lang);
    }
}
