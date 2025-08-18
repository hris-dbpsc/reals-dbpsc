<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->employeenumber = '1111-F2014';
        $user->firstname = 'UserFirst';
        $user->middlename = 'UserMiddle';
        $user->lastname = 'UserLast';
        $user->email = 'user@dbpsc.com.ph';
        $user->password = Hash::make('user');
        $user->isactive = '1'; // 1 for active, 0 for inactive, 2 for suspended
        $user->token = '';
        $user->save();
    }
}
