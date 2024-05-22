<?php
namespace App\TelegramBot\Actions;

use App\Repositories\UserRepository;

class SetAddress
{
    public static function set(string $chat_id, string $lang): void
    {
        (new UserRepository())->setAddress($chat_id, $lang);
    }
}
