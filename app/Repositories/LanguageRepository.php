<?php

namespace App\Repositories;

use App\Repositories\Interfaces\LanguageInterface;
use Illuminate\Support\Facades\DB;

class LanguageRepository
{
    public function setLanguage(string $chat, string $lang): void
    {
        DB::table('users')->where('telegram_id', $chat)->update(['lang' => $lang]);
    }
}
