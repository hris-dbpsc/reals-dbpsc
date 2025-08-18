<?php

namespace App\Exports;

use App\Models\Superadmin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuperadminExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $query = Superadmin::select(
            'employeenumber',
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
            'EMPLOYEE NUMBER',
            'LASTNAME',
            'FIRSTNAME',
            'MIDDLENAME',
            'EMAIL',
            'CONTACT',
            'STATUS',
        ];
    }
}
