<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Client([
            'clientname' => $row['clientname'],
            'clientshortname' => $row['clientshortname'],
            'clienttype' => $row['clienttype'],
            'clientgeolocation' => $row['clientgeolocation'],
            'clientstreetview' => $row['clientstreetview'],
        ]); 
    }
}