<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
     public function run(): void
    {
        // Users
        User::create(['name' => 'SuperAdmin', 'email' => 'super@psynapse.com', 'password' => bcrypt('password'), 'role' => 'superadmin']);
        User::create(['name' => 'Admin', 'email' => 'admin@psynapse.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        User::create(['name' => 'Test User', 'email' => 'user@psynapse.com', 'password' => bcrypt('password'), 'role' => 'user']);

        // Topics and Templates
        $this->call(TopicAndTemplateSeeder::class);
    }
}
