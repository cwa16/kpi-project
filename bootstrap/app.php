<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withSchedule(function (Schedule $schedule) {

        // Reminder Input
        $schedule->command('email:send-reminder-input')
            ->daily()
            ->when(fn () => now()->day >= 10 && now()->day <= 15)
            ->between('07:00', '14:00')
            ->withoutOverlapping();

        // Reminder Check1
        $schedule->command('email:send-reminder-check1')
            ->daily()
            ->when(fn () => now()->day >= 10 && now()->day <= 15)
            ->between('07:00', '14:00')
            ->withoutOverlapping();
    
        // Reminder Check2
        $schedule->command('email:send-reminder-check2')
            ->daily()
            ->when(fn () => now()->day >= 16 && now()->day <= 20)
            ->between('07:00', '14:00')
            ->withoutOverlapping();

        // Reminder Approve
        $schedule->command('email:send-reminder-approve')
            ->daily()
            ->when(fn () => now()->day >= 21 && now()->day <= 25)
            ->between('07:00', '14:00')
            ->withoutOverlapping();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
