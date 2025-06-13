<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ReminderCheck2Email;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendEmailReminderCheck2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-reminder-check2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for check 2';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $details = [
            'title' => 'Notifikasi Pengingat Pengecekan (Check 2) Data KPI',
            'greetings' => 'Yth. ',
            'name' => '',
            'msg' => 'Mengingatkan kembali untuk melakukan pengecekan (Check 2) pada data KPI dan data pendukung KPI yang sudah diinputkan.',
            'msg2' => 'Jika anda sudah melakukan pengecekan data KPI dan data pendukung KPI, abaikan email ini.',
            'closing' => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email' => '',
        ];

        $sendTo = DB::table('employees')
            ->whereIn('employees.role', ['Checker Div 1', 'Checker Div 2'])
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name'] = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderCheck2Email::dispatch($details);
            }
        }
        Log::channel('laravel-worker')->info(
            'ReminderCheck2Email sent to: ' . $details['email']
        );
        // Log::channel('laravel-worker')->info('Email reminders for Check 2 have been dispatched successfully.');
    }
}
