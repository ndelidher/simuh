@extends('layouts.app')
@section('title', 'Daftar Masjid')

@section('content')
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Daftar Masjid</h1>
        <p style="font-size:13px;color:#718096;">Kelola data masjid sesuai cakupan wilayah Anda</p>
    </div>
    @if(!auth()->user()->isAdminPP())
    <a href="{{ route('masjid.create') }}" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;display:flex;align-items:center;gap:6px;">
        + Tambah Masjid
    </a>
    @endif
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('masjid.index') }}">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px 16px;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:14px;">
    <div style="display:flex;flex-direction:column;gap:4px;">
        <label style="font-size:11px;color:#718096;">Cari nama masjid</label>
        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Ketik nama masjid..." style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;width:200px;">
    </div>
    @if(auth()->user()->isAdminPDM() || auth()->user()->isSuperAdmin() || auth()->user()->isAdminPP() || auth()->user()->isAdminPWM())
    <div style="display:flex;flex-direction:column;gap:4px;">
        <label style="font-size:11px;color:#718096;">PCM</label>
        <select name="pcm_id" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:150px;">
            <option value="">Semua PCM</option>
        </select>
    </div>
    @endif
    <div style="display:flex;flex-direction:column;gap:4px;">
        <label style="font-size:11px;color:#718096;">Kategori Unggulan</label>
        <select name="kategori" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:150px;">
            <option value="">Semua Kategori</option>
            <option value="MU_WILAYAH" {{ request('kategori')=='MU_WILAYAH'?'selected':'' }}>MU Wilayah</option>
            <option value="MU_DAERAH"  {{ request('kategori')=='MU_DAERAH'?'selected':'' }}>MU Daerah</option>
            <option value="MU_CABANG"  {{ request('kategori')=='MU_CABANG'?'selected':'' }}>MU Cabang</option>
            <option value="MU_RANTING" {{ request('kategori')=='MU_RANTING'?'selected':'' }}>MU Ranting</option>
        </select>
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
        <label style="font-size:11px;color:#718096;">Status</label>
        <select name="aktif" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:110px;">
            <option value="">Semua</option>
            <option value="1" {{ request('aktif')==='1'?'selected':'' }}>Aktif</option>
            <option value="0" {{ request('aktif')==='0'?'selected':'' }}>Non-aktif</option>
        </select>
    </div>
    <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;">Cari</button>
    <a href="{{ route('masjid.index') }}" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">Reset</a>
</div>
</form>

{{-- Tabel --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:32px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama Masjid</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:100px;">PRM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:120px;">Kategori</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:110px;">Kota/Kab.</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Status</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:90px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($masjid as $i => $m)
            <tr style="border-bottom:0.5px solid #f0f4ec;">
                <td style="padding:9px 12px;font-size:12px;color:#718096;">{{ $masjid->firstItem() + $i }}</td>
                <td style="padding:9px 12px;font-size:13px;font-weight:500;">{{ $m->nama }}</td>
                <td style="padding:9px 12px;font-size:12px;color:#718096;">{{ $m->prm->nama ?? '-' }}</td>
                <td style="padding:9px 12px;">
                    @if($m->kategori_unggulan == 'MU_WILAYAH')
                        <span style="background:#EEEDFE;color:#3C3489;font-size:10px;padding:2px 8px;border-radius:20px;">MU Wilayah</span>
                    @elseif($m->kategori_unggulan == 'MU_DAERAH')
                        <span style="background:#E1F5EE;color:#085041;font-size:10px;padding:2px 8px;border-radius:20px;">MU Daerah</span>
                    @elseif($m->kategori_unggulan == 'MU_CABANG')
                        <span style="background:#FAEEDA;color:#633806;font-size:10px;padding:2px 8px;border-radius:20px;">MU Cabang</span>
                    @elseif($m->kategori_unggulan == 'MU_RANTING')
                        <span style="background:#FAECE7;color:#4A1B0C;font-size:10px;padding:2px 8px;border-radius:20px;">MU Ranting</span>
                    @else
                        <span style="background:#F1EFE8;color:#5F5E5A;font-size:10px;padding:2px 8px;border-radius:20px;">—</span>
                    @endif
                </td>
                <td style="padding:9px 12px;font-size:12px;color:#718096;">{{ $m->kota_kabupaten ?? '-' }}</td>
                <td style="padding:9px 12px;text-align:center;">
                    @if($m->aktif)
                        <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:2px 8px;border-radius:20px;">Aktif</span>
                    @else
                        <span style="background:#F7C1C1;color:#791F1F;font-size:10px;padding:2px 8px;border-radius:20px;">Non-aktif</span>
                    @endif
                </td>
                <td style="padding:9px 12px;text-align:center;">
                    <div style="display:flex;gap:5px;justify-content:center;">
                        <a href="{{ route('masjid.show', $m) }}" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;" title="Lihat">&#128065;</a>
                        @if(!auth()->user()->isAdminPP())
                        <a href="{{ route('masjid.edit', $m) }}" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;" title="Edit">&#9998;</a>
                        <form method="POST" action="{{ route('masjid.destroy', $m) }}" onsubmit="return confirm('Hapus masjid ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#A32D2D;background:transparent;cursor:pointer;font-size:13px;" title="Hapus">&#128465;</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:32px;text-align:center;color:#718096;font-size:13px;">
                    Belum ada data masjid. 
                    @if(!auth()->user()->isAdminPP())
                    <a href="{{ route('masjid.create') }}" style="color:#3B6D11;">Tambah masjid pertama</a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        <span>Menampilkan {{ $masjid->firstItem() ?? 0 }}–{{ $masjid->lastItem() ?? 0 }} dari {{ $masjid->total() }} masjid</span>
        <div>{{ $masjid->withQueryString()->links() }}</div>
    </div>
</div>
@endsection