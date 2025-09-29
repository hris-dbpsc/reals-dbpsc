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
        $query = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select(
                'clients.clientname as clientname',
                'clients.clientshortname as clientshortname',
                'clients.clienttype as clienttype',
                'branches.branchname',
                'branches.branchgeolocation',
                'branches.branchstreetview',
                'branches.branchcontact',
                'branches.branchcontactperson',
                'branches.branchaddress',
                'branches.branchregion',
                'branches.branchprovince',
                'branches.branchcity'
            );

        if ($this->clientshortname && $this->clientshortname !== 'ALL CLIENTS') {
            $query->where('clients.clientshortname', $this->clientshortname);
        }

        $query->orderBy('clients.clientname', 'asc');

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
