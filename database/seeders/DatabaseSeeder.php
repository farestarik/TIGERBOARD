<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\LaratrustSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()

    {
        $this->call(LaratrustSeeder::class);


        $user = User::create([
            "name" => "TIGER",
            "username" => "admin",
            "password" => bcrypt("password"),
            "email" => "admin@gmail.com",
            'token' => Str::random(27),
            'active' => 1,
            'created_by' => 0
        ]);

        $user->addRole("owner");

        Profile::create([
            'user_id' => $user->id
        ]);


        $this->call([
            SettingsSeeder::class
        ]);
    }
}
