<?php
namespace App\Repositories;

use Dotenv\Util\Str;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function setAddress(string $chat, string $address): void
    {
        DB::table('users')->where('telegram_id', $chat)->update(['address' => $address]);
    }

    public function setLanguage(string $chat, string $lang): void
    {
        DB::table('users')->where('telegram_id', $chat)->update(['lang' => $lang]);
    }

    public function setName(string $chat, string $name)
    {
        DB::table('users')->where('telegram_id', $chat)->update(['name' => $name]);
    }
    public function logout(string $chat):void
    {
        DB::table('users')->where('telegram_id', $chat)->delete();
    }
}
