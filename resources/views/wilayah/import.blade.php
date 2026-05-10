@extends('layouts.app')
@section('title', 'Import Data Wilayah')

@section('content')
<div style="margin-bottom:16px;">
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Import Data Wilayah</h1>
    <p style="font-size:13px;color:#718096;">Upload file Excel PRM untuk mengisi data PWM, PDM, PCM, dan PRM sekaligus</p>
</div>

{{-- Panduan format --}}
<div style="background:#EAF3DE;border:0.5px solid #b7dca3;border-radius:12px;padding:16px;margin-bottom:16px;">
    <div style="font-size:13px;font-weight:500;color:#1C4A2A;margin-bottom:8px;">📋 Format File Excel yang Diperlukan</div>
    <div style="font-size:12px;color:#27500A;margin-bottom:8px;">File harus memiliki kolom berurutan sebagai berikut:</div>
    <div style="background:#fff;border-radius:8px;overflow:hidden;border:0.5px solid #b7dca3;">
        <table style="width:100%;border-collapse:collapse;font-size:12px;">
            <thead>
                <tr style="background:#EAF3DE;">
                    <th style="padding:8px 12px;text-align:left;color:#27500A;border-bottom:0.5px solid #b7dca3;">Kolom A</th>
                    <th style="padding:8px 12px;text-align:left;color:#27500A;border-bottom:0.5px solid #b7dca3;">Kolom B</th>
                    <th style="padding:8px 12px;text-align:left;color:#27500A;border-bottom:0.5px solid #b7dca3;">Kolom C</th>
                    <th style="padding:8px 12px;text-align:left;color:#27500A;border-bottom:0.5px solid #b7dca3;">Kolom D</th>
                    <th style="padding:8px 12px;text-align:left;color:#27500A;border-bottom:0.5px solid #b7dca3;">Kolom E</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:8px 12px;border-bottom:0.5px solid #f0f4ec;font-weight:500;">ID_PRM</td>
                    <td style="padding:8px 12px;border-bottom:0.5px solid #f0f4ec;">Ranting (PRM)</td>
                    <td style="padding:8px 12px;border-bottom:0.5px solid #f0f4ec;">Cabang (PCM)</td>
                    <td style="padding:8px 12px;border-bottom:0.5px solid #f0f4ec;">Daerah (PDM)</td>
                    <td style="padding:8px 12px;border-bottom:0.5px solid #f0f4ec;">Wilayah (PWM)</td>
                </tr>
                <tr style="background:#F4F7F1;">
                    <td style="padding:8px 12px;font-size:11px;color:#718096;">PRM-001</td>
                    <td style="padding:8px 12px;font-size:11px;color:#718096;">Ranting Pajang</td>
                    <td style="padding:8px 12px;font-size:11px;color:#718096;">Cabang Laweyan</td>
                    <td style="padding:8px 12px;font-size:11px;color:#718096;">Daerah Surakarta</td>
                    <td style="padding:8px 12px;font-size:11px;color:#718096;">Wilayah Jawa Tengah</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="font-size:11px;color:#3B6D11;margin-top:8px;">
        ✓ Baris pertama adalah header (akan dilewati otomatis)<br>
        ✓ Data duplikat akan diabaikan secara otomatis<br>
        ✓ Format file: .xlsx atau .xls
    </div>
</div>

{{-- Form upload --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:24px;max-width:500px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;">Upload File Excel</div>

    <form method="POST" action="{{ route('wilayah.import.proses') }}" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom:16px;">
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:6px;">Pilih file Excel <span style="color:red;">*</span></label>
            <input type="file" name="file" accept=".xlsx,.xls" required
                style="width:100%;border:0.5px solid #ccc;border-radius:8px;padding:8px 10px;font-size:13px;">
            @error('file')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>

        <div style="background:#FFF8E1;border:0.5px solid #FFE082;border-radius:8px;padding:10px 12px;margin-bottom:16px;font-size:12px;color:#7B5800;">
            ⚠ Pastikan format file sesuai panduan di atas sebelum mengupload.
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit"
                style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
                ⬆ Import Sekarang
            </button>
            <a href="{{ route('wilayah.prm') }}"
                style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection