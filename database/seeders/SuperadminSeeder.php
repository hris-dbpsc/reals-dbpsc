<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Superadmin;
use Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = new Superadmin();
        $superadmin->employeenumber = '3895-F2014';
        $superadmin->firstname = 'Symon';
        $superadmin->middlename = 'Lagman';
        $superadmin->lastname = 'Magtoto';
        $superadmin->email = 'hris@dbpsc.com.ph';
        $superadmin->password = Hash::make('adminadmin');
        $superadmin->isactive = '1'; // 1 for active, 0 for inactive, 2 for suspended"
        $superadmin->token = '';
        $superadmin->save();
    }
}
