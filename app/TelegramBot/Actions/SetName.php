<?php
namespace App\TelegramBot\Actions;

use App\Repositories\UserRepository;

class SetName
{
    public static function set(string $chat_id, string $name): void
    {
        (new UserRepository())->updateUser($chat_id, ['name' => $name]);
    }
}
