<?php
namespace App\TelegramBot\Actions;

use App\Repositories\UserRepository;

class SetName
{
    public static function set(string $chat_id, string $name): void
    {
        (new UserRepository())->setName($chat_id, $name);
    }
}
