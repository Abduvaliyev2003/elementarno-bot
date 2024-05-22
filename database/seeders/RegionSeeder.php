<?php

namespace Database\Seeders;

use App\Models\Regions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $regions = [
                [
                    "name" => "Qoraqalpog‘iston"
                ],
                [
                    "name" => "Andijon"
                ],
                [
                    "name" => "Buxoro"
                ],
                [
                    "name" => "Jizzax"
                ],
                [
                    "name" => "Qashqadaryo"
                ],
                [
                    "name" => "Navoiy"
                ],
                [
                    "name" => "Namangan"
                ],
                [
                    "name" => "Samarqand"
                ],
                [
                    "name" => "Surxandaryo"
                ],
                [
                    "name" => "Sirdaryo"
                ],
                [
                    "name" => "Farg‘ona"
                ],
                [
                    "name" => "Xorazm"
                ],
                [
                    "name" => "Toshkent"
                ]
                ];

        foreach($regions as $region)
        {
            Regions::create([
                'name' => $region['name']
            ]);
        }

    }
}
