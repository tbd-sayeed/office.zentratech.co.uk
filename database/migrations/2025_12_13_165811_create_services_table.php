<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->enum('service_type', ['domain_hosting', 'web_mobile_dev', 'custom'])->default('custom');
            
            // Common fields for all service types
            $table->string('service_name');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->date('start_date');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Domain & Hosting specific fields
            $table->string('domain_name')->nullable();
            $table->string('hosting_package')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('provider_name')->nullable(); // Namecheap, GoDaddy, etc.
            $table->text('credentials')->nullable(); // Encrypted credentials
            
            // Web/Mobile Development specific fields
            $table->enum('project_type', ['website', 'mobile_app'])->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            
            // Custom service fields
            $table->string('custom_service_type')->nullable(); // SEO, maintenance, retainer, etc.
            
            // Reminder tracking
            $table->boolean('reminder_30_sent')->default(false);
            $table->boolean('reminder_15_sent')->default(false);
            $table->boolean('reminder_7_sent')->default(false);
            $table->boolean('contract_reminder_15_sent')->default(false);
            $table->boolean('contract_reminder_7_sent')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
