<?php

namespace App\Imports;

use App\Models\Branches;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BranchesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Branches([
            'clientid' => $row['clientid'],
            'clienttype' => $row['clienttype'],
            'branchname' => $row['branchname'],
            'branchgeolocation' => $row['branchgeolocation'],
            'branchstreetview' => $row['branchstreetview'],
            'branchcontact' => $row['branchcontact'],
            'branchcontactperson' => $row['branchcontactperson'],
            'branchaddress' => $row['branchaddress'],
            'branchregion' => $row['branchregion'],
            'branchprovince' => $row['branchprovince'],
            'branchcity' => $row['branchcity'],
        ]);
    }
}