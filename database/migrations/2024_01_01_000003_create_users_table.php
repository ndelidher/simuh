<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');

            // Role: super_admin, admin_pp, admin_pwm, admin_pdm, admin_pcm, admin_prm, admin_masjid
            $table->enum('role', [
                'super_admin',
                'admin_pp',
                'admin_pwm',
                'admin_pdm',
                'admin_pcm',
                'admin_prm',
                'admin_masjid',
            ]);

            // Scope akses — hanya satu yang diisi sesuai role
            $table->foreignId('pwm_id')->nullable()->constrained('pwm')->nullOnDelete();
            $table->foreignId('pdm_id')->nullable()->constrained('pdm')->nullOnDelete();
            $table->foreignId('pcm_id')->nullable()->constrained('pcm')->nullOnDelete();
            $table->foreignId('prm_id')->nullable()->constrained('prm')->nullOnDelete();
            $table->foreignId('masjid_id')->nullable()->constrained('masjid')->nullOnDelete();

            $table->string('telepon', 20)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
