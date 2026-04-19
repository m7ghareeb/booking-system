<?php

use App\Models\Employee;
use App\Models\Service;
use App\Services\ServiceSlotAvailability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Carbon::setTestNow(Carbon::now()->setTimeFromTimeString('17:00'));

Route::get('/test', function () {

    $employees = Employee::get();
    $service = Service::find(1);

    $availablity = (new ServiceSlotAvailability($employees, $service))
        ->forPeriod(
            Carbon::now()->startOfDay(),
            Carbon::now()->addDay()->endOfDay()
        );

    dd($availablity->firstAvailableDate());
});

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';
