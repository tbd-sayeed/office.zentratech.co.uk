<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate from enum to dynamic service_type_id and project_type_id.
     */
    public function up(): void
    {
        // Ensure service types exist
        $this->seedServiceTypes();
        $this->seedProjectTypes();

        // Add new columns
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('service_type_id')->nullable()->after('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_type_id')->nullable()->after('service_type_id')->constrained()->nullOnDelete();
        });

        // Map old service_type enum to service_type_id
        $typeMap = [
            'domain_hosting' => 'Domain & Hosting',
            'web_mobile_dev' => 'Web Development',
            'custom' => 'Other / Custom',
            'web_design' => 'Web Design',
            'web_development' => 'Web Development',
            'mobile_app_development' => 'Mobile App Development',
            'ecommerce_solutions' => 'E-Commerce Solutions',
            'graphic_design' => 'Graphic Design',
            'ui_ux_design' => 'UI/UX Design',
            'seo' => 'Search Engine Optimisation (SEO)',
            'it_consultancy_support' => 'IT Consultancy, Support & Maintenance',
        ];

        $projectMap = ['website' => 'Website', 'mobile_app' => 'Mobile App'];

        foreach ($typeMap as $enumVal => $typeName) {
            $st = DB::table('service_types')->where('name', $typeName)->first();
            if ($st) {
                DB::table('services')->where('service_type', $enumVal)->update(['service_type_id' => $st->id]);
            }
        }

        foreach ($projectMap as $enumVal => $typeName) {
            $pt = DB::table('project_types')->where('name', $typeName)->first();
            if ($pt) {
                DB::table('services')->where('project_type', $enumVal)->update(['project_type_id' => $pt->id]);
            }
        }

        // Assign default service_type for any unmigrated rows
        $defaultSt = DB::table('service_types')->where('name', 'Other / Custom')->first();
        if ($defaultSt) {
            DB::table('services')->whereNull('service_type_id')->update(['service_type_id' => $defaultSt->id]);
        }

        // Drop old columns and make service_type_id required
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('service_type');
            $table->dropColumn('project_type');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
        });
        DB::statement('ALTER TABLE services MODIFY service_type_id BIGINT UNSIGNED NOT NULL');
        Schema::table('services', function (Blueprint $table) {
            $table->foreign('service_type_id')->references('id')->on('service_types')->cascadeOnDelete();
        });

        // Revert client_id to required (assign orphan services to first client if any)
        $firstClient = DB::table('clients')->first();
        if ($firstClient) {
            DB::table('services')->whereNull('client_id')->update(['client_id' => $firstClient->id]);
        }
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
        DB::statement('ALTER TABLE services MODIFY client_id BIGINT UNSIGNED NOT NULL');
        Schema::table('services', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
        });
    }

    private function seedServiceTypes(): void
    {
        $types = [
            ['name' => 'Web Design', 'form_section' => 'project_based', 'sort_order' => 1],
            ['name' => 'Web Development', 'form_section' => 'project_based', 'sort_order' => 2],
            ['name' => 'Mobile App Development', 'form_section' => 'project_based', 'sort_order' => 3],
            ['name' => 'E-Commerce Solutions', 'form_section' => 'project_based', 'sort_order' => 4],
            ['name' => 'Graphic Design', 'form_section' => 'project_based', 'sort_order' => 5],
            ['name' => 'UI/UX Design', 'form_section' => 'project_based', 'sort_order' => 6],
            ['name' => 'Search Engine Optimisation (SEO)', 'form_section' => 'project_based', 'sort_order' => 7],
            ['name' => 'IT Consultancy, Support & Maintenance', 'form_section' => 'project_based', 'sort_order' => 8],
            ['name' => 'Domain & Hosting', 'form_section' => 'domain_hosting', 'sort_order' => 9],
            ['name' => 'Other / Custom', 'form_section' => 'custom', 'sort_order' => 10],
        ];
        foreach ($types as $t) {
            DB::table('service_types')->insertOrIgnore(array_merge($t, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedProjectTypes(): void
    {
        $types = [
            ['name' => 'Website', 'sort_order' => 1],
            ['name' => 'Mobile App', 'sort_order' => 2],
            ['name' => 'Web Application', 'sort_order' => 3],
            ['name' => 'E-commerce', 'sort_order' => 4],
        ];
        foreach ($types as $t) {
            DB::table('project_types')->insertOrIgnore(array_merge($t, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]));
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
            $table->dropForeign(['project_type_id']);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->string('service_type')->default('custom');
            $table->string('project_type')->nullable();
        });
    }
};
