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
        $query = User::select(
            'employeenumber',
            'clientname',
            'branchname',
            'departmentname',
            'position',
            'lastname',
            'firstname',
            'middlename',
            'dateofbirth',
            'gender', 
            'assumptiondate',
            'startdate',
            'enddate',
            'templatename',
            'hiretype',
            'wagetype',
            'paymode',
            'salaryrate', 
            'billingrate',
            'positioncategory',
            'leavecredits',
            'civilstatus',
            'address',
            'contact',
            'tin',
            'sssnumber',
            'phicnumber',
            'hdmfnumber',
            'lastpaydate',
            'region',
            'email'
        );

        if ($this->clientshortname && $this->clientshortname !== 'ALL CLIENTS') {
            $query->where('clientname', $this->clientshortname);
        }

        $query->orderBy('clientname', 'asc');

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
