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
        $superadmin->employee_number = '3895-F2014';
        $superadmin->firstname = 'Symon';
        $superadmin->middlename = 'Lagman';
        $superadmin->lastname = 'Magtoto';
        $superadmin->email = 'hris@dbpsc.com.ph';
        $superadmin->password = Hash::make('adminadmin');
        $superadmin->token = '';
        $superadmin->save();
    }
}
