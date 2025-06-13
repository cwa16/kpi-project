<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ReminderInputEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MailController;

class SendEmailReminderInput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-reminder-input';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for input';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $details = [
            'title' => 'Notifikasi Pengingat Pengisian Data KPI',
            'greetings' => 'Yth. ',
            'name' => '',
            'msg' => 'Mengingatkan kembali untuk mengisi data KPI dan mengupload data pendukung KPI yang sesuai.',
            'msg2' => 'Jika anda sudah mengisi data KPI dan data pendukung KPI, abaikan email ini.',
            'closing' => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email' => '',
        ];

        $sendTo = DB::table('employees')
            ->where('employees.role', '!=', 'Mng Approver')
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name'] = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderInputEmail::dispatch($details);
            }
            Log::channel('laravel-worker')->info(
                'ReminderInputEmail sent to: ' . $details['email']
            );
        }

        // Log::channel('laravel-worker')->info('Email reminder for input sent successfully.');
    }
}
