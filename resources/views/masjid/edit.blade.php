@extends('layouts.app')
@section('title', 'Edit Masjid — ' . $masjid->nama)

@section('content')
<div style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:10px;">
        <a href="{{ route('masjid.index') }}" style="color:#3B6D11;text-decoration:none;">Daftar Masjid</a>
        <span>›</span>
        <a href="{{ route('masjid.show', $masjid) }}" style="color:#3B6D11;text-decoration:none;">{{ $masjid->nama }}</a>
        <span>›</span>
        <span>Edit</span>
    </div>
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Edit Masjid</h1>
    <p style="font-size:13px;color:#718096;">{{ $masjid->nama }}</p>
</div>

@php
    // Ambil data wilayah existing
    $prm     = $masjid->prm;
    $pcm     = $prm?->pcm;
    $pdm     = $pcm?->pdm;
    $pwm     = $pdm?->pwm;
@endphp

<form method="POST" action="{{ route('masjid.update', $masjid) }}" enctype="multipart/form-data">
@csrf @method('PUT')

{{-- Lokasi Wilayah (Cascading Dropdown) --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:4px;">📍 Lokasi Wilayah</div>
    <div style="font-size:12px;color:#718096;margin-bottom:16px;">Pilih bertahap: PWM → PDM → PCM → PRM</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- PWM --}}
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PWM (Wilayah) <span style="color:red;">*</span></label>
            <select id="sel_pwm" onchange="loadPDM(this.value)"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#fff;">
                <option value="">-- Pilih PWM --</option>
                @foreach($pwmList as $p)
                <option value="{{ $p->id }}" {{ $pwm && $pwm->id == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- PDM --}}
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PDM (Daerah) <span style="color:red;">*</span></label>
            <select id="sel_pdm" onchange="loadPCM(this.value)"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#fff;">
                <option value="">-- Pilih PDM --</option>
                @if($pdm)
                <option value="{{ $pdm->id }}" selected>{{ $pdm->nama }}</option>
                @endif
            </select>
        </div>

        {{-- PCM --}}
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PCM (Cabang) <span style="color:red;">*</span></label>
            <select id="sel_pcm" onchange="loadPRM(this.value)"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#fff;">
                <option value="">-- Pilih PCM --</option>
                @if($pcm)
                <option value="{{ $pcm->id }}" selected>{{ $pcm->nama }}</option>
                @endif
            </select>
        </div>

        {{-- PRM --}}
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PRM (Ranting) <span style="color:red;">*</span></label>
            <select id="sel_prm" name="prm_id" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;background:#fff;">
                <option value="">-- Pilih PRM --</option>
                @if($prm)
                <option value="{{ $prm->id }}" selected>{{ $prm->nama }}</option>
                @endif
            </select>
            @error('prm_id')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>

    </div>
</div>

{{-- Identitas Masjid --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        🕌 Identitas Masjid
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kode Masjid</label>
            <input type="text" value="{{ $masjid->kode }}" disabled
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;background:#f9f9f9;color:#718096;">
            <span style="font-size:11px;color:#a0aec0;">Kode tidak dapat diubah</span>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama Masjid <span style="color:red;">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $masjid->nama) }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            @error('nama')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Tahun Berdiri</label>
            <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri', $masjid->tahun_berdiri) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                min="1800" max="{{ date('Y') }}">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Status Tanah</label>
            <select name="status_tanah" style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih --</option>
                @foreach(['Wakaf','Milik','Sewa','Pinjam'] as $st)
                <option value="{{ $st }}" {{ old('status_tanah', $masjid->status_tanah) == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div style="grid-column:span 2;">
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Alamat</label>
            <input type="text" name="alamat" value="{{ old('alamat', $masjid->alamat) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kota / Kabupaten</label>
            <input type="text" name="kota_kabupaten" value="{{ old('kota_kabupaten', $masjid->kota_kabupaten) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Provinsi</label>
            <input type="text" name="provinsi" value="{{ old('provinsi', $masjid->provinsi) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
    </div>
</div>

{{-- Data Fisik --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        📐 Data Fisik
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Luas Tanah (m²)</label>
            <input type="number" name="luas_tanah" value="{{ old('luas_tanah', $masjid->luas_tanah) }}" min="0"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Luas Bangunan (m²)</label>
            <input type="number" name="luas_bangunan" value="{{ old('luas_bangunan', $masjid->luas_bangunan) }}" min="0"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kapasitas Jamaah</label>
            <input type="number" name="kapasitas_jamaah" value="{{ old('kapasitas_jamaah', $masjid->kapasitas_jamaah) }}" min="0"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
    </div>
</div>

{{-- Kontak Pengelola / Takmir --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        📞 Kontak Pengelola / Takmir
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama Pengelola / Takmir</label>
            <input type="text" name="nama_pengelola" value="{{ old('nama_pengelola', $masjid->nama_pengelola) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Nama ketua takmir / pengelola">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">No HP / WhatsApp</label>
            <input type="text" name="hp_pengelola" value="{{ old('hp_pengelola', $masjid->hp_pengelola) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="08xxxxxxxxxx">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Email Pengelola</label>
            <input type="email" name="email_pengelola" value="{{ old('email_pengelola', $masjid->email_pengelola) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="email@contoh.com">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Website Masjid</label>
            <input type="url" name="website" value="{{ old('website', $masjid->website) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="https://masjid.contoh.com">
        </div>
    </div>
</div>

{{-- Status Unggulan --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:20px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        ⭐ Status Masjid Unggulan
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kategori Unggulan</label>
            <select name="kategori_unggulan" style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Belum ditetapkan --</option>
                @foreach(['MU_WILAYAH'=>'MU Wilayah (PWM)','MU_DAERAH'=>'MU Daerah (PDM)','MU_CABANG'=>'MU Cabang (PCM)','MU_RANTING'=>'MU Ranting (PRM)'] as $val => $label)
                <option value="{{ $val }}" {{ old('kategori_unggulan', $masjid->kategori_unggulan) == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Tanggal Penetapan</label>
            <input type="date" name="tanggal_penetapan"
                value="{{ old('tanggal_penetapan', $masjid->tanggal_penetapan?->format('Y-m-d')) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:8px;">Status Aktif</label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $masjid->aktif) ? 'checked' : '' }}
                    style="width:16px;height:16px;">
                <label for="aktif" style="font-size:13px;color:#4a5568;">Masjid aktif</label>
            </div>
        </div>
    </div>
</div>

{{-- Tombol --}}
<div style="display:flex;gap:10px;">
    <button type="submit"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
        💾 Simpan Perubahan
    </button>
    <a href="{{ route('masjid.show', $masjid) }}"
        style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">
        Batal
    </a>
</div>

</form>
@endsection

@push('scripts')
<script>
// Data existing untuk prefill cascading
const existingPwmId = {{ $pwm?->id ?? 'null' }};
const existingPdmId = {{ $pdm?->id ?? 'null' }};
const existingPcmId = {{ $pcm?->id ?? 'null' }};
const existingPrmId = {{ $prm?->id ?? 'null' }};

function setLoading(selectEl, text = 'Memuat...') {
    selectEl.innerHTML = `<option value="">${text}</option>`;
    selectEl.disabled = true;
    selectEl.style.background = '#f9f9f9';
}

function setReady(selectEl) {
    selectEl.disabled = false;
    selectEl.style.background = '#fff';
}

function resetSelect(selectEl, placeholder) {
    selectEl.innerHTML = `<option value="">${placeholder}</option>`;
    selectEl.disabled = true;
    selectEl.style.background = '#f9f9f9';
}

function loadPDM(pwmId, selectedId = null) {
    const pdm = document.getElementById('sel_pdm');
    const pcm = document.getElementById('sel_pcm');
    const prm = document.getElementById('sel_prm');
    if (!selectedId) { resetSelect(pcm, '-- Pilih PCM --'); resetSelect(prm, '-- Pilih PRM --'); }
    if (!pwmId) { resetSelect(pdm, '-- Pilih PDM --'); return; }
    setLoading(pdm, 'Memuat PDM...');
    fetch(`/api/pdm-by-pwm/${pwmId}`)
        .then(r => r.json())
        .then(data => {
            pdm.innerHTML = '<option value="">-- Pilih PDM --</option>';
            data.forEach(d => {
                const sel = selectedId && d.id == selectedId ? 'selected' : '';
                pdm.innerHTML += `<option value="${d.id}" ${sel}>${d.nama}</option>`;
            });
            setReady(pdm);
            if (selectedId) loadPCM(selectedId, existingPcmId);
        });
}

function loadPCM(pdmId, selectedId = null) {
    const pcm = document.getElementById('sel_pcm');
    const prm = document.getElementById('sel_prm');
    if (!selectedId) resetSelect(prm, '-- Pilih PRM --');
    if (!pdmId) { resetSelect(pcm, '-- Pilih PCM --'); return; }
    setLoading(pcm, 'Memuat PCM...');
    fetch(`/api/pcm-by-pdm/${pdmId}`)
        .then(r => r.json())
        .then(data => {
            pcm.innerHTML = '<option value="">-- Pilih PCM --</option>';
            data.forEach(d => {
                const sel = selectedId && d.id == selectedId ? 'selected' : '';
                pcm.innerHTML += `<option value="${d.id}" ${sel}>${d.nama}</option>`;
            });
            setReady(pcm);
            if (selectedId) loadPRM(selectedId, existingPrmId);
        });
}

function loadPRM(pcmId, selectedId = null) {
    const prm = document.getElementById('sel_prm');
    if (!pcmId) { resetSelect(prm, '-- Pilih PRM --'); return; }
    setLoading(prm, 'Memuat PRM...');
    fetch(`/api/prm-by-pcm/${pcmId}`)
        .then(r => r.json())
        .then(data => {
            prm.innerHTML = '<option value="">-- Pilih PRM --</option>';
            data.forEach(d => {
                const sel = selectedId && d.id == selectedId ? 'selected' : '';
                prm.innerHTML += `<option value="${d.id}" ${sel}>${d.nama}</option>`;
            });
            setReady(prm);
        });
}

// Saat halaman dimuat, prefill dropdown dari data existing
window.addEventListener('DOMContentLoaded', () => {
    if (existingPwmId) {
        loadPDM(existingPwmId, existingPdmId);
    }
});
</script>
@endpush