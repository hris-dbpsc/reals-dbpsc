<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $query = Client::select(
            'clientname',
            'clientshortname',
            'clienttype',
            'clientgeolocation',
            'clientstreetview'
        );

        $query->orderBy('clientshortname', 'asc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'CLIENT NAME',
            'CLIENT SHORTNAME',
            'CLIENT TYPE',
            'CLIENT GEOLOCATION',
            'CLIENT STREETVIEW',
        ];
    }
}
