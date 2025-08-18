<?php

namespace App\Exports;

use App\Models\Branches;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $clientshortname;

    public function __construct($clientshortname = null)
    {
        $this->clientshortname = $clientshortname;
    }
    public function collection()
    {
        $query = Branches::select(
            'clientname',
            'clientshortname',
            'clienttype',
            'branchname',
            'branchgeolocation',
            'branchstreetview',
            'branchcontact',
            'branchcontactperson',
            'branchaddress',
            'branchregion',
            'branchprovince',
            'branchcity'
        );

     
        if ($this->clientshortname && $this->clientshortname !== 'ALL CLIENTS') {
            $query->where('clientshortname', $this->clientshortname);
        }

        $query->orderBy('clientname', 'asc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'CLIENT NAME',
            'CLIENT SHORTNAME',
            'CLIENT TYPE',
            'BRANCH NAME',
            'BRANCH GEOLOCATION',
            'BRANCH STREETVIEW',
            'BRANCH CONTACT',
            'BRANCH CONTACT PERSON',
            'BRANCH ADDRESS',
            'BRANCH REGION',
            'BRANCH PROVINCE',
            'BRANCH CITY',
        ];
    }
}
