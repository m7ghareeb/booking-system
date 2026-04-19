<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ServiceResource;
use App\Models\Employee;
use App\Models\Service;

class HomeController extends Controller
{
    public function __invoke()
    {
        $employees = Employee::select('id', 'name', 'profile_photo_url')->orderBy('name', 'asc')->get();
        $services = Service::select('id', 'title', 'slug', 'duration', 'price')->get();

        return inertia()->render('Home', [
            'employees' => EmployeeResource::collection($employees),
            'services'  => ServiceResource::collection($services),
        ]);
    }
}
