<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AddressRepository
{
    public function setAddress(string $chat, string $address): void
    {
        DB::table('users')->where('telegram_id', $chat)->update(['address' => $address]);
    }
}
