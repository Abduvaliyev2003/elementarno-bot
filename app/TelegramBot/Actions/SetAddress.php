<?php
namespace App\TelegramBot\Actions;

use App\Repositories\UserRepository;

class SetAddress
{
    public static function set(string $chat_id, string $address): void
    {
        (new UserRepository())->updateUser($chat_id, ['address' => $address]);
    }
}
