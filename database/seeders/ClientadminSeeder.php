<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clientadmin;
use Hash;

class ClientadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientadmin = new Clientadmin();
        $clientadmin->firstname = 'ClientadminFirst';
        $clientadmin->middlename = 'ClientadminMiddle';
        $clientadmin->lastname = 'ClientadminLast';
        $clientadmin->email = 'clientadmin@dbpsc.com.ph';
        $clientadmin->password = Hash::make('clientadmin');
        $clientadmin->isactive = '1'; // 1 for active, 0 for inactive, 2 for suspended
        $clientadmin->token = '';
        $clientadmin->save();
    }
}
