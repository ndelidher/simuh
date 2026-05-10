<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masjid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prm_id')->constrained('prm')->onDelete('restrict');

            // Identitas
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->string('alamat')->nullable();
            $table->string('kelurahan', 60)->nullable();
            $table->string('kecamatan', 60)->nullable();
            $table->string('kota_kabupaten', 60)->nullable();
            $table->string('provinsi', 60)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Kontak
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 150)->nullable();

            // Profil
            $table->year('tahun_berdiri')->nullable();
            $table->integer('luas_tanah')->nullable()->comment('m2');
            $table->integer('luas_bangunan')->nullable()->comment('m2');
            $table->integer('kapasitas_jamaah')->nullable();
            $table->string('status_tanah', 50)->nullable()->comment('Wakaf, Milik, Sewa, dll');
            $table->string('foto')->nullable();

            // Status unggulan
            $table->enum('kategori_unggulan', ['MU_WILAYAH','MU_DAERAH','MU_CABANG','MU_RANTING'])->nullable();
            $table->date('tanggal_penetapan')->nullable();

            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['prm_id', 'aktif']);
            $table->index('kategori_unggulan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masjid');
    }
};
