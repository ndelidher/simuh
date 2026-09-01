@extends('layouts.app')
@section('title', ($mode=='create'?'Tambah':'Edit') . ' PRM')
@section('content')

<div style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:10px;">
        <a href="{{ route('wilayah.prm') }}" style="color:#3B6D11;text-decoration:none;">Data PRM</a>
        <span>›</span>
        <span>{{ $mode=='create' ? 'Tambah' : 'Edit' }} PRM</span>
    </div>
    <h1 style="font-size:20px;font-weight:500;">{{ $mode=='create' ? 'Tambah' : 'Edit' }} PRM</h1>
</div>

<form method="POST" action="{{ $mode=='create' ? route('wilayah.prm.store') : route('wilayah.prm.update', $data) }}">
@csrf
@if($mode=='edit') @method('PUT') @endif

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PWM <span style="color:red;">*</span></label>
            <select id="sel_pwm" onchange="loadPDM(this.value)"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih PWM --</option>
                @foreach($pwmList as $p)
                <option value="{{ $p->id }}" {{ old('pwm_id', $data?->pcm?->pdm?->pwm_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PDM <span style="color:red;">*</span></label>
            <select id="sel_pdm" onchange="loadPCM(this.value)"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih PDM --</option>
                @foreach($pdmList as $p)
                <option value="{{ $p->id }}" {{ old('pdm_id', $data?->pcm?->pdm_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">PCM <span style="color:red;">*</span></label>
            <select name="pcm_id" id="sel_pcm"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih PCM --</option>
                @foreach($pcmList as $p)
                <option value="{{ $p->id }}" {{ old('pcm_id', $data->pcm_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            @error('pcm_id')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Kode PRM <span style="color:red;">*</span></label>
            <input type="text" name="kode" value="{{ old('kode', $data->kode ?? '') }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            @error('kode')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama PRM <span style="color:red;">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $data->nama ?? '') }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            @error('nama')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:8px;">Status</label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $data->aktif ?? true) ? 'checked' : '' }} style="width:16px;height:16px;">
                <label for="aktif" style="font-size:13px;">Aktif</label>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;">
    <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
        💾 {{ $mode=='create' ? 'Simpan' : 'Perbarui' }}
    </button>
    <a href="{{ route('wilayah.prm') }}" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">Batal</a>
</div>
</form>
@endsection
@push('scripts')
<script>
const existingPdmId = {{ $data?->pcm?->pdm_id ?? 'null' }};
const existingPcmId = {{ $data?->pcm_id ?? 'null' }};

function loadPDM(pwmId) {
    const pdm = document.getElementById('sel_pdm');
    const pcm = document.getElementById('sel_pcm');
    pcm.innerHTML = '<option value="">-- Pilih PCM --</option>';
    if (!pwmId) { pdm.innerHTML = '<option value="">-- Pilih PDM --</option>'; return; }
    pdm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pdm-by-pwm/${pwmId}`)
        .then(r => r.json())
        .then(data => {
            pdm.innerHTML = '<option value="">-- Pilih PDM --</option>';
            data.forEach(d => {
                const sel = d.id == existingPdmId ? 'selected' : '';
                pdm.innerHTML += `<option value="${d.id}" ${sel}>${d.nama}</option>`;
            });
            if (existingPdmId) loadPCM(existingPdmId);
        });
}

function loadPCM(pdmId) {
    const pcm = document.getElementById('sel_pcm');
    if (!pdmId) { pcm.innerHTML = '<option value="">-- Pilih PCM --</option>'; return; }
    pcm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pcm-by-pdm/${pdmId}`)
        .then(r => r.json())
        .then(data => {
            pcm.innerHTML = '<option value="">-- Pilih PCM --</option>';
            data.forEach(d => {
                const sel = d.id == existingPcmId ? 'selected' : '';
                pcm.innerHTML += `<option value="${d.id}" ${sel}>${d.nama}</option>`;
            });
        });
}

window.addEventListener('DOMContentLoaded', () => {
    const pwmSel = document.getElementById('sel_pwm');
    if (pwmSel.value) loadPDM(pwmSel.value);
});
</script>
@endpush
