<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pimpinan Wilayah Muhammadiyah
        Schema::create('pwm', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->string('provinsi', 60)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Pimpinan Daerah Muhammadiyah
        Schema::create('pdm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pwm_id')->constrained('pwm')->onDelete('restrict');
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->string('kota_kabupaten', 60)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Pimpinan Cabang Muhammadiyah
        Schema::create('pcm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pdm_id')->constrained('pdm')->onDelete('restrict');
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->string('kecamatan', 60)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Pimpinan Ranting Muhammadiyah
        Schema::create('prm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pcm_id')->constrained('pcm')->onDelete('restrict');
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->string('kelurahan', 60)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prm');
        Schema::dropIfExists('pcm');
        Schema::dropIfExists('pdm');
        Schema::dropIfExists('pwm');
    }
};
