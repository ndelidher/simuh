<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master indikator (bisa dikonfigurasi admin)
        Schema::create('indikator', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()->comment('JAMAAH_1, JAMAAH_2, JARIYAH_1, dst');
            $table->string('nama', 100);
            $table->enum('kelompok', ['jamaah', 'jariyah', 'jamiyah']);
            $table->string('satuan', 30)->nullable()->comment('orang, juta rupiah, kegiatan, konten');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Data indikator per masjid per bulan
        Schema::create('data_indikator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjid')->onDelete('cascade');
            $table->foreignId('indikator_id')->constrained('indikator')->onDelete('restrict');
            $table->year('tahun');
            $table->tinyInteger('bulan')->comment('1-12');
            $table->decimal('nilai', 12, 2)->default(0);
            $table->text('keterangan')->nullable();

            // Audit
            $table->foreignId('input_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('update_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'terkirim', 'diverifikasi'])->default('draft');
            $table->timestamp('terkirim_at')->nullable();
            $table->timestamp('diverifikasi_at')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Satu nilai per masjid per indikator per bulan per tahun
            $table->unique(['masjid_id', 'indikator_id', 'tahun', 'bulan'], 'uq_data_indikator');
            $table->index(['masjid_id', 'tahun', 'bulan']);
            $table->index(['tahun', 'bulan']);
        });

        // Log perubahan nilai indikator
        Schema::create('log_data_indikator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_indikator_id')->constrained('data_indikator')->onDelete('cascade');
            $table->decimal('nilai_lama', 12, 2)->nullable();
            $table->decimal('nilai_baru', 12, 2);
            $table->string('aksi', 20)->comment('create, update, delete');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_data_indikator');
        Schema::dropIfExists('data_indikator');
        Schema::dropIfExists('indikator');
    }
};
