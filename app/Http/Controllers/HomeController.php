<?php

namespace App\Http\Controllers;

use App\Data\EmployeeData;
use App\Data\ServiceData;
use App\Models\Employee;
use App\Models\Service;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Home', [
            'employees' => EmployeeData::collect(
                Employee::select('id', 'name', 'slug', 'profile_photo_url')
                    ->orderBy('name', 'asc')
                    ->get()
            ),
            'services' => ServiceData::collect(
                Service::select('id', 'title', 'slug', 'duration', 'price')
                    ->get()
            ),
        ]);
    }
}
