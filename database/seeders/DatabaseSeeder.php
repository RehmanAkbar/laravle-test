<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::destroy(1);
        $admin = User::create([
            'name' => 'Admin User',
            'user_name' => 'admin_user',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'registered_at' => now()
        ]);

        $admin->user_role = User::ADMIN_ROLE;
        $admin->save();
    }
}
