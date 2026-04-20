<?php

namespace App\Http\Controllers;

use App\Data\EmployeeData;
use App\Models\Employee;
use Inertia\Inertia;

class EmpolyeeServiceIndexController extends Controller
{
    public function __invoke(Employee $employee)
    {
        $employee->loadMissing('services');

        return Inertia::render('Employee', [
            'employee' => EmployeeData::from($employee),
        ]);
    }
}
