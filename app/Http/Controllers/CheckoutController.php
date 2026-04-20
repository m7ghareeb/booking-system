<?php

namespace App\Http\Controllers;

use App\Data\EmployeeData;
use App\Data\ServiceData;
use App\Models\Employee;
use App\Models\Service;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __invoke(Service $service, Employee $employee)
    {
        return Inertia::render('Checkout', [
            'employee' => $employee->exists ? EmployeeData::from($employee) : null,
            'service'  => ServiceData::from($service),
        ]);
    }
}
