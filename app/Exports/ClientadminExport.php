<?php

namespace App\Exports;

use App\Models\Clientadmin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientadminExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $query = Clientadmin::select(
            'clientname',
            'lastname',
            'firstname',
            'middlename',
            'email',
            'contact',
            \DB::raw("CASE 
                WHEN isactive = 1 THEN 'ACTIVE' 
                WHEN isactive = 2 THEN 'SUSPENDED' 
                WHEN isactive = 0 THEN 'INACTIVE' 
                ELSE '' END as status"),
        );


        $query->orderBy('lastname', 'asc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'CLIENT NAME',
            'LASTNAME',
            'FIRSTNAME',
            'MIDDLENAME',
            'EMAIL',
            'CONTACT',
            'STATUS',
        ];
    }
}
