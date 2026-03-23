<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('currency', 3)->default('GBP')->after('paid_amount');
        });
        DB::table('services')->update(['currency' => 'GBP']);

        Schema::table('service_team_assignments', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->after('agreed_amount');
        });
        DB::table('service_team_assignments')->update(['currency' => 'USD']);

        Schema::table('payments', function (Blueprint $table) {
            $table->string('currency', 3)->default('GBP')->after('amount');
        });
        DB::table('payments')->update(['currency' => 'GBP']);

        Schema::table('team_member_payments', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->after('amount');
        });
        DB::table('team_member_payments')->update(['currency' => 'USD']);
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('service_team_assignments', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('team_member_payments', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
