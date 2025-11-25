<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Reminder Input
        $schedule->command('email:send-reminder-input')
            ->daily()
            ->when(fn() => now()->day >= 10 && now()->day <= 15)
            ->between('07:00', '16:00')
            ->withoutOverlapping();

        // Reminder Check1
        $schedule->command('email:send-reminder-check1')
            ->daily()
            ->when(fn() => now()->day >= 10 && now()->day <= 15)
            ->between('07:00', '16:00')
            ->withoutOverlapping();

        // Reminder Check2
        $schedule->command('email:send-reminder-check2')
            ->daily()
            ->when(fn() => now()->day >= 16 && now()->day <= 20)
            ->between('07:00', '16:00')
            ->withoutOverlapping();

        // Reminder Approve
        $schedule->command('email:send-reminder-approve')
            ->daily()
            ->when(fn() => now()->day >= 21 && now()->day <= 25)
            ->between('07:00', '16:00')
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
