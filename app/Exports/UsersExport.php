<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
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
        $query = User::leftJoin('clients', 'users.clientid', '=', 'clients.id')
            ->select(
                'users.employeenumber',
                'clients.clientname as clientname',
                'users.branchname',
                'users.departmentname',
                'users.position',
                'users.lastname',
                'users.firstname',
                'users.middlename',
                'users.dateofbirth',
                'users.gender',
                'users.assumptiondate',
                'users.startdate',
                'users.enddate',
                'users.templatename',
                'users.hiretype',
                'users.wagetype',
                'users.paymode',
                'users.salaryrate',
                'users.billingrate',
                'users.positioncategory',
                'users.leavecredits',
                'users.civilstatus',
                'users.address',
                'users.contact',
                'users.tin',
                'users.sssnumber',
                'users.phicnumber',
                'users.hdmfnumber',
                'users.lastpaydate',
                'users.region',
                'users.email'
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
            'Employee ID',
            'Client Name',
            'Branch Name',
            'Department',
            'Position',
            'Last Name',
            'First Name',
            'Middle Name',
            'Date of Birth',
            'Gender',
            'Assumption Date',
            'Start Date',
            'End Date',
            'Template Name',
            'Hire Type',
            'Wage Type',
            'Pay Mode',
            'Salary Rate',
            'Billing Rate',
            'Position Category',
            'Leave Credits',
            'Civil Status',
            'Address',
            'Contact',
            'TIN',
            'SSS Number',
            'PHIC Number',
            'HDMF Number',
            'Last Pay Date',
            'Region',
            'Email'
        ];
    }
}
