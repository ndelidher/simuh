<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedIndikator();
        $this->seedSuperAdmin();
    }

    private function seedIndikator(): void
    {
        $now = Carbon::now();
        $indikators = [
            [
                'kode'      => 'JAMAAH_1',
                'nama'      => "Rata-rata jumlah Jamaah sholat subuh",
                'kelompok'  => 'jamaah',
                'satuan'    => 'orang',
                'deskripsi' => 'Rata-rata jumlah jamaah yang mengikuti sholat subuh berjamaah per bulan',
                'urutan'    => 1,
            ],
            [
                'kode'      => 'JAMAAH_2',
                'nama'      => "Rata-rata jumlah Jamaah pengajian rutin",
                'kelompok'  => 'jamaah',
                'satuan'    => 'orang',
                'deskripsi' => 'Rata-rata jumlah jamaah yang mengikuti pengajian rutin per bulan',
                'urutan'    => 2,
            ],
            [
                'kode'      => 'JARIYAH_1',
                'nama'      => 'Jumlah Infaq Masjid per bulan',
                'kelompok'  => 'jariyah',
                'satuan'    => 'juta rupiah',
                'deskripsi' => 'Total penerimaan infaq masjid dalam satu bulan (dalam juta rupiah)',
                'urutan'    => 3,
            ],
            [
                'kode'      => 'JARIYAH_2',
                'nama'      => 'Jumlah Alokasi Dana Infaq Masjid per bulan',
                'kelompok'  => 'jariyah',
                'satuan'    => 'juta rupiah',
                'deskripsi' => 'Total alokasi/pengeluaran dana infaq masjid dalam satu bulan (dalam juta rupiah)',
                'urutan'    => 4,
            ],
            [
                'kode'      => 'JAMIYAH_1',
                'nama'      => 'Jumlah kegiatan Rapat Takmir per bulan',
                'kelompok'  => 'jamiyah',
                'satuan'    => 'kegiatan',
                'deskripsi' => 'Jumlah penyelenggaraan rapat takmir dalam satu bulan',
                'urutan'    => 5,
            ],
            [
                'kode'      => 'JAMIYAH_2',
                'nama'      => 'Jumlah konten dalam media sosial per bulan',
                'kelompok'  => 'jamiyah',
                'satuan'    => 'konten',
                'deskripsi' => 'Jumlah konten yang dipublikasikan di media sosial masjid dalam satu bulan',
                'urutan'    => 6,
            ],
            [
                'kode'      => 'JAMIYAH_3',
                'nama'      => 'Jumlah Kegiatan Unggulan yang diselenggarakan',
                'kelompok'  => 'jamiyah',
                'satuan'    => 'kegiatan',
                'deskripsi' => 'Jumlah kegiatan unggulan/spesial yang diselenggarakan masjid dalam satu bulan',
                'urutan'    => 7,
            ],
        ];

        foreach ($indikators as $item) {
            DB::table('indikator')->updateOrInsert(
                ['kode' => $item['kode']],
                array_merge($item, ['aktif' => true, 'created_at' => $now, 'updated_at' => $now])
            );
        }
    }

    private function seedSuperAdmin(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'superadmin'],
            [
                'name'       => 'Super Administrator',
                'username'   => 'superadmin',
                'email'      => 'superadmin@simuh.muhammadiyah.or.id',
                'password'   => Hash::make('SimuhAdmin@2024'),
                'role'       => 'super_admin',
                'aktif'      => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
