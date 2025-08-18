<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new Admin();
        $admin->employeenumber = '0000-F2014';
        $admin->firstname = 'AdminFirst';
        $admin->middlename = 'AdminMiddle';
        $admin->lastname = 'AdminLast';
        $admin->email = 'admin@dbpsc.com.ph';
        $admin->password = Hash::make('admin');
        $admin->isactive = '1'; // 1 for active, 0 for inactive, 2 for suspended
        $admin->token = '';
        $admin->save();
    }
}
