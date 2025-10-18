<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [[
            'id' => Str::random(13),
            'username' => "qknot",
            'email' => "qknot0.3@gmail.com",
            'password' => Hash::make('73939133'),
            'role' => "admin"
        ],[
            'id' => Str::random(13),
            'username' => "rahul",
            'email' => "rahul@gmail.com",
            'password' => Hash::make('rahul73939133'),
            'role' => "member"
        ]];
        User::insert($datas);
    }
}
