<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Get the row number from the heading row (if available)
        $rowNumber = $row['row_number'] ?? null;

        $errors = [];

        // Check for duplicate employee number
        $duplicateEmployee = User::where('employeenumber', $row['employeeid'])->first();
        if ($duplicateEmployee) {
            $errors[] = "Duplicate employee number '{$row['employeeid']}' found at row " . ($rowNumber ?? '[unknown]');
        }

        // Check for duplicate email
        if (!empty($row['email'])) {
            $duplicateEmail = User::where('email', $row['email'])->first();
            if ($duplicateEmail) {
            $errors[] = "Duplicate email '{$row['email']}' found at row " . ($rowNumber ?? '[unknown]');
            }
        }

        // If there are errors, display all errors and the full row data
        if (!empty($errors)) {
            $rowDetails = print_r($row, true);
            $errorMessage = "Import errors at row " . ($rowNumber ?? '[unknown]') . ":\n" .
            implode("\n", $errors) . "\nRow data:\n" . $rowDetails;
            throw new \Exception($errorMessage);
        }

        return new User([
            'employeenumber' => $row['employeeid'],
            'clientname' => $row['client'],
            'branchname' => $row['branch_name'],
            'departmentname' => $row['department'],
            'position' => $row['position'],
            'lastname' => $row['lastname'],
            'firstname' => $row['firstname'],
            'middlename' => $row['middlename'],
            'dateofbirth' => $row['dob'],
            'gender' => $row['gender'],
            'assumptiondate' => $row['assumption_date'],
            'startdate' => $row['start_date'],
            'enddate' => $row['end_date'],
            'templatename' => $row['template_name'],
            'hiretype' => $row['hire_type'],
            'wagetype' => $row['wage_type'],
            'paymode' => $row['paymode'],
            'salaryrate' => $row['salary_rate'],
            'billingrate' => $row['billing_rate'],
            'positioncategory' => $row['position_category'],
            'leavecredits' => $row['leave_credits'],
            'civilstatus' => $row['civil_status'],
            'address' => $row['address'],
            'contact' => $row['cellnumber'],
            'tin' => $row['tin'],
            'sssnumber' => $row['sss_number'],
            'phicnumber' => $row['phic_number'],
            'hdmfnumber' => $row['hdmf_number'],
            'lastpaydate' => $row['last_paydate'],
            'region' => $row['region'],
            'email' => $row['email'] ?? null,
        ]);
    }
}