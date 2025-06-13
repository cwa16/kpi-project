<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ReminderApproveEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendEmailReminderApprove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-reminder-approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for approval';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $details = [
            'title' => 'Notifikasi Pengingat Persetujuan Data KPI',
            'greetings' => 'Yth. ',
            'name' => '',
            'msg' => 'Mengingatkan kembali untuk melakukan persetujuan (Approve) pada data KPI dan data pendukung KPI yang sudah diinputkan.',
            'msg2' => 'Jika anda sudah melakukan persetujuan data KPI dan data pendukung KPI, abaikan email ini.',
            'closing' => 'Terima kasih atas perhatian dan kerjasamanya.',
            'email' => '',
        ];

        $sendTo = DB::table('employees')
            ->where('employees.role', '=', 'Mng Approver')
            ->select('employees.email', 'employees.name')
            ->get();

        foreach ($sendTo as $email) {
            $details['email'] = $email->email;
            $details['name'] = $email->name;

            if ($details['email'] !== null && $details['email'] !== '' && $details['email'] !== 0) {
                ReminderApproveEmail::dispatch($details);
            }
        }
        Log::channel('laravel-worker')->info(
            'ReminderApproveEmail sent to: ' . $details['email']
        );
        // Log::channel('laravel-worker')->info('Email reminders for approval have been dispatched successfully.');
    }
}
