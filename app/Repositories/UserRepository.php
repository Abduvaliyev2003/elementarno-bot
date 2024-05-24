<?php

namespace App\Repositories;

use Dotenv\Util\Str;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    public function updateUser(string $chat, array $data): void
    {
        DB::table('users')->where('telegram_id', $chat)->update($data);
    }

    public function logout(string $chat): void
    {
        DB::table('users')->where('telegram_id', $chat)->delete();
    }

    public static  function userPage(string $chat)
    {
        $data =  DB::table('users')->where('telegram_id', $chat)->first();
        return $data;
    }
}
