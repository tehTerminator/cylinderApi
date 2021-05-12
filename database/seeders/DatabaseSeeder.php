<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        User::create([
            'title' => 'Prateek Kher',
            'username' => 'admin',
            'password' => Hash::make('myPass'),
            'mobile' => '9425760707',
            'designation' => 'Administrator',
            'is_admin' => true
        ]);
    }
}
