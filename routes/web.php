<?php

use App\Models\Employee;
use App\Models\Service;
use App\Services\ScheduleAvailabilityService;
use App\Services\SlotRangeGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/test', function () {
    // $employee = Employee::find(1);
    // $service = Service::find(1);

    // $availablity = (new ScheduleAvailabilityService($employee, $service))
    //     ->forPeriod(
    //         Carbon::now()->startOfDay(),
    //         Carbon::now()->addMonth()->endOfDay()
    //     );

    $genertor = (new SlotRangeGenerator(
        Carbon::now()->startOfDay(),
        Carbon::now()->addDay()->endOfDay()
    ));

    dd($genertor->generate(30));
});

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';
