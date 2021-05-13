<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ward;
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

        $wards = [
            ['title' => 'DCHC', 'capacity' => 28],
            ['title' => 'Male Ward', 'capacity' => 45],
            ['title' => 'General ICU', 'capacity' => 35],
            ['title' => 'SNCU', 'capacity' => 14],
            ['title' => 'Covid ICU', 'capacity' => 10],
            ['title' => 'Female Ward', 'capacity' => 30],
        ];

        for($i = 0; $i < count($wards); $i++) {
            Ward::create($wards[$i]);
        }
    }
}
