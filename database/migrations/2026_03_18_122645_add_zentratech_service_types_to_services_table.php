<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Expands service_type to include all ZentraTech service offerings.
     */
    public function up(): void
    {
        $enum = [
            'domain_hosting',      // Domain & Hosting (existing)
            'web_mobile_dev',      // Web/Mobile Development (existing)
            'custom',              // Custom/Other (existing)
            'web_design',
            'web_development',
            'mobile_app_development',
            'ecommerce_solutions',
            'graphic_design',
            'ui_ux_design',
            'seo',
            'it_consultancy_support',
        ];
        $enumString = "'" . implode("','", $enum) . "'";
        DB::statement("ALTER TABLE services MODIFY service_type ENUM($enumString) NOT NULL DEFAULT 'custom'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE services MODIFY service_type ENUM('domain_hosting', 'web_mobile_dev', 'custom') NOT NULL DEFAULT 'custom'");
    }
};
