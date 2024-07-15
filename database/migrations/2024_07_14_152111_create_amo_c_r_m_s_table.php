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
        Schema::create('amo_c_r_m_s', function (Blueprint $table) {
            $table->uuid('client_id')->primary();
            $table->string('client_secret');
            $table->string('subdomain');
            $table->text('access_token');
            $table->string('redirect_uri');
            $table->string('token_type');
            $table->text('refresh_token');
            $table->bigInteger('expires_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amo_c_r_m_s');
    }
};
