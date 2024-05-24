<?php
namespace App\TelegramBot\Actions;


use App\Repositories\UserRepository;

class SetUserPage
{
    public static function set(string $chat_id, string $type): void
    {
        (new UserRepository())->updateUser($chat_id, ['type' => $type]);
    }
}
