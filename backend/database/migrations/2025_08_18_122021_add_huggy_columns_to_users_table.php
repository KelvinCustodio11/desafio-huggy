<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('huggy_user_id')->nullable()->index();
            $table->string('huggy_company_id')->nullable()->index();
            $table->text('huggy_access_token')->nullable();
            $table->text('huggy_refresh_token')->nullable();
            $table->timestamp('huggy_expires_at')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
