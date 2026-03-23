<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Mail\ContractReminderMail;
use App\Models\EmailLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendContractReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send contract expiry reminders for web/mobile dev services (15, 7 days before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for contracts ending soon...');

        $reminderDays = [15, 7];
        $sentCount = 0;

        foreach ($reminderDays as $days) {
            $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
            
            $services = Service::whereHas('serviceType', fn($q) => $q->where('form_section', 'project_based'))
                ->where('is_active', true)
                ->whereNotNull('contract_end_date')
                ->whereDate('contract_end_date', $targetDate)
                ->with('client')
                ->get();

            foreach ($services as $service) {
                // Check if reminder already sent for this day
                $reminderFlag = match($days) {
                    15 => 'contract_reminder_15_sent',
                    7 => 'contract_reminder_7_sent',
                    default => null
                };

                if ($reminderFlag && $service->$reminderFlag) {
                    continue;
                }

                try {
                    Mail::to($service->client->email)->send(
                        new ContractReminderMail($service, $service->client, $days)
                    );

                    $service->update([$reminderFlag => true]);

                    EmailLog::create([
                        'client_id' => $service->client_id,
                        'service_id' => $service->id,
                        'email_type' => 'contract_reminder',
                        'recipient_email' => $service->client->email,
                        'subject' => 'Service Contract Ending Soon - ' . $service->service_name,
                        'body' => "Contract reminder sent ($days days)",
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);

                    $sentCount++;
                    $this->info("Sent {$days}-day contract reminder for {$service->service_name} to {$service->client->email}");
                } catch (\Exception $e) {
                    EmailLog::create([
                        'client_id' => $service->client_id,
                        'service_id' => $service->id,
                        'email_type' => 'contract_reminder',
                        'recipient_email' => $service->client->email,
                        'subject' => 'Service Contract Ending Soon - ' . $service->service_name,
                        'body' => "Contract reminder failed ($days days)",
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);

                    $this->error("Failed to send contract reminder for {$service->service_name}: " . $e->getMessage());
                }
            }
        }

        $this->info("Sent {$sentCount} contract reminders.");
        return Command::SUCCESS;
    }
}
