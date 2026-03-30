<?php

use Illuminate\Support\Facades\Schedule;

// Reminder Input (Dikirim jam 08:00 pagi setiap tanggal 10-15)
Schedule::command('email:send-reminder-input')
    ->dailyAt('08:00')
    ->when(fn() => now()->day >= 10 && now()->day <= 15)
    ->withoutOverlapping();

// Reminder Check1 (Dikirim jam 08:00 pagi setiap tanggal 10-15)
Schedule::command('email:send-reminder-check1')
    ->dailyAt('08:00')
    ->when(fn() => now()->day >= 10 && now()->day <= 15)
    ->withoutOverlapping();

// Reminder Check2 (Dikirim jam 08:00 pagi setiap tanggal 16-20)
Schedule::command('email:send-reminder-check2')
    ->dailyAt('08:00')
    ->when(fn() => now()->day >= 16 && now()->day <= 20)
    ->withoutOverlapping();

// Reminder Approve (Dikirim jam 08:00 pagi setiap tanggal 21-25)
Schedule::command('email:send-reminder-approve')
    ->dailyAt('08:00')
    ->when(fn() => now()->day >= 21 && now()->day <= 25)
    ->withoutOverlapping();
