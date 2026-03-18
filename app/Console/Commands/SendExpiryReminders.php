<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Mail\ExpiryReminderMail;
use App\Models\EmailLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send expiry reminders for domain/hosting services (30, 15, 7 days before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for services expiring soon...');

        $reminderDays = [30, 15, 7];
        $sentCount = 0;

        foreach ($reminderDays as $days) {
            $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
            
            $services = Service::where('service_type', 'domain_hosting')
                ->where('is_active', true)
                ->whereNotNull('expiration_date')
                ->whereDate('expiration_date', $targetDate)
                ->with('client')
                ->get();

            foreach ($services as $service) {
                // Check if reminder already sent for this day
                $reminderFlag = match($days) {
                    30 => 'reminder_30_sent',
                    15 => 'reminder_15_sent',
                    7 => 'reminder_7_sent',
                    default => null
                };

                if ($reminderFlag && $service->$reminderFlag) {
                    continue;
                }

                try {
                    Mail::to($service->client->email)->send(
                        new ExpiryReminderMail($service, $service->client, $days)
                    );

                    $service->update([$reminderFlag => true]);

                    EmailLog::create([
                        'client_id' => $service->client_id,
                        'service_id' => $service->id,
                        'email_type' => 'expiry_reminder',
                        'recipient_email' => $service->client->email,
                        'subject' => 'Renewal Reminder: ' . $service->service_name . ' expires in ' . $days . ' days',
                        'body' => "Expiry reminder sent ($days days)",
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);

                    $sentCount++;
                    $this->info("Sent {$days}-day reminder for {$service->service_name} to {$service->client->email}");
                } catch (\Exception $e) {
                    EmailLog::create([
                        'client_id' => $service->client_id,
                        'service_id' => $service->id,
                        'email_type' => 'expiry_reminder',
                        'recipient_email' => $service->client->email,
                        'subject' => 'Renewal Reminder: ' . $service->service_name,
                        'body' => "Expiry reminder failed ($days days)",
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);

                    $this->error("Failed to send reminder for {$service->service_name}: " . $e->getMessage());
                }
            }
        }

        $this->info("Sent {$sentCount} expiry reminders.");
        return Command::SUCCESS;
    }
}
