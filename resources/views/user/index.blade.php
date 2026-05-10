@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Manajemen User</h1>
        <p style="font-size:13px;color:#718096;">Kelola akun user · Total: {{ $users->total() }} user</p>
    </div>
    <a href="{{ route('user.create') }}"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;">
        + Tambah User
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('user.index') }}" id="filterForm">
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:14px 16px;margin-bottom:14px;">

    {{-- Baris 1: Cari, Role, Status --}}
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:10px;">
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">Cari nama / username</label>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama atau username..."
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;width:180px;">
        </div>
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">Role</label>
            <select name="role" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:140px;">
                <option value="">Semua Role</option>
                @foreach(['admin_pp'=>'Admin PP','admin_pwm'=>'Admin PWM','admin_pdm'=>'Admin PDM','admin_pcm'=>'Admin PCM','admin_prm'=>'Admin PRM','admin_masjid'=>'Admin Masjid'] as $val=>$lbl)
                <option value="{{ $val }}" {{ request('role')==$val?'selected':'' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">Status</label>
            <select name="aktif" style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:110px;">
                <option value="">Semua</option>
                <option value="1" {{ request('aktif')==='1'?'selected':'' }}>Aktif</option>
                <option value="0" {{ request('aktif')==='0'?'selected':'' }}>Non-aktif</option>
            </select>
        </div>
        <button type="submit" style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:0 16px;height:32px;font-size:13px;cursor:pointer;">Cari</button>
        <a href="{{ route('user.index') }}" style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:0 12px;height:32px;font-size:13px;color:#718096;text-decoration:none;display:flex;align-items:center;">Reset</a>
    </div>

    {{-- Baris 2: Filter Wilayah Cascading --}}
    @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin_pp')
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding-top:10px;border-top:0.5px solid #f0f4ec;">
        <span style="font-size:11px;color:#718096;align-self:center;">Filter wilayah:</span>

        {{-- PWM --}}
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PWM</label>
            <select name="pwm_id" id="f_pwm" onchange="filterLoadPDM(this.value)"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:170px;">
                <option value="">Semua PWM</option>
                @foreach($pwmList as $p)
                <option value="{{ $p->id }}" {{ request('pwm_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- PDM --}}
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PDM</label>
            <select name="pdm_id" id="f_pdm" onchange="filterLoadPCM(this.value)"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:170px;">
                <option value="">Semua PDM</option>
                @foreach($pdmList as $p)
                <option value="{{ $p->id }}" {{ request('pdm_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- PCM --}}
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PCM</label>
            <select name="pcm_id" id="f_pcm" onchange="filterLoadPRM(this.value)"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:170px;">
                <option value="">Semua PCM</option>
                @foreach($pcmList as $p)
                <option value="{{ $p->id }}" {{ request('pcm_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- PRM --}}
        <div style="display:flex;flex-direction:column;gap:3px;">
            <label style="font-size:11px;color:#718096;">PRM</label>
            <select name="prm_id" id="f_prm"
                style="height:32px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;width:170px;">
                <option value="">Semua PRM</option>
                @foreach($prmList as $p)
                <option value="{{ $p->id }}" {{ request('prm_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

</div>
</form>

{{-- Tabel --}}
<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:36px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Username</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Role</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Wilayah</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Status</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:70px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $u)
            @php
                $rc = [
                    'super_admin'  => ['bg'=>'#1C4A2A','c'=>'#fff'],
                    'admin_pp'     => ['bg'=>'#EEEDFE','c'=>'#3C3489'],
                    'admin_pwm'    => ['bg'=>'#E1F5EE','c'=>'#085041'],
                    'admin_pdm'    => ['bg'=>'#EAF3DE','c'=>'#27500A'],
                    'admin_pcm'    => ['bg'=>'#FAEEDA','c'=>'#633806'],
                    'admin_prm'    => ['bg'=>'#FAECE7','c'=>'#4A1B0C'],
                    'admin_masjid' => ['bg'=>'#F1EFE8','c'=>'#5F5E5A'],
                ][$u->role] ?? ['bg'=>'#eee','c'=>'#333'];
            @endphp
            <tr style="border-bottom:0.5px solid #f0f4ec;">
                <td style="padding:8px 12px;font-size:12px;color:#718096;">{{ $users->firstItem() + $i }}</td>
                <td style="padding:8px 12px;font-size:13px;font-weight:500;">{{ $u->name }}</td>
                <td style="padding:8px 12px;font-size:12px;color:#718096;font-family:monospace;">{{ $u->username }}</td>
                <td style="padding:8px 12px;">
                    <span style="background:{{ $rc['bg'] }};color:{{ $rc['c'] }};font-size:10px;padding:2px 8px;border-radius:20px;white-space:nowrap;">
                        {{ $u->role_label }}
                    </span>
                </td>
                <td style="padding:8px 12px;font-size:12px;color:#718096;">
                    @if($u->prm) {{ $u->prm->nama }}
                    @elseif($u->pcm) {{ $u->pcm->nama }}
                    @elseif($u->pdm) {{ $u->pdm->nama }}
                    @elseif($u->pwm) {{ $u->pwm->nama }}
                    @else —
                    @endif
                </td>
                <td style="padding:8px 12px;text-align:center;">
                    @if($u->aktif)
                        <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:2px 8px;border-radius:20px;">Aktif</span>
                    @else
                        <span style="background:#F7C1C1;color:#791F1F;font-size:10px;padding:2px 8px;border-radius:20px;">Non-aktif</span>
                    @endif
                </td>
                <td style="padding:8px 12px;text-align:center;">
                    <div style="display:flex;gap:5px;justify-content:center;">
                        <a href="{{ route('user.edit', $u) }}"
                            style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#718096;text-decoration:none;font-size:13px;">✎</a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('user.destroy', $u) }}" onsubmit="return confirm('Hapus user {{ addslashes($u->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:26px;height:26px;border:0.5px solid #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#A32D2D;background:transparent;cursor:pointer;font-size:13px;">🗑</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                    Tidak ada user ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        <span>Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user</span>
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cascading filter wilayah — load otomatis saat pilih
function filterLoadPDM(pwmId) {
    const pdm = document.getElementById('f_pdm');
    const pcm = document.getElementById('f_pcm');
    const prm = document.getElementById('f_prm');
    if (pcm) { pcm.innerHTML = '<option value="">Semua PCM</option>'; }
    if (prm) { prm.innerHTML = '<option value="">Semua PRM</option>'; }
    if (!pwmId || !pdm) return;
    pdm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pdm-by-pwm/${pwmId}`)
        .then(r => r.json())
        .then(data => {
            pdm.innerHTML = '<option value="">Semua PDM</option>';
            data.forEach(d => pdm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
        });
}

function filterLoadPCM(pdmId) {
    const pcm = document.getElementById('f_pcm');
    const prm = document.getElementById('f_prm');
    if (prm) { prm.innerHTML = '<option value="">Semua PRM</option>'; }
    if (!pdmId || !pcm) return;
    pcm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/pcm-by-pdm/${pdmId}`)
        .then(r => r.json())
        .then(data => {
            pcm.innerHTML = '<option value="">Semua PCM</option>';
            data.forEach(d => pcm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
        });
}

function filterLoadPRM(pcmId) {
    const prm = document.getElementById('f_prm');
    if (!pcmId || !prm) return;
    prm.innerHTML = '<option value="">Memuat...</option>';
    fetch(`/api/prm-by-pcm/${pcmId}`)
        .then(r => r.json())
        .then(data => {
            prm.innerHTML = '<option value="">Semua PRM</option>';
            data.forEach(d => prm.innerHTML += `<option value="${d.id}">${d.nama}</option>`);
        });
}
</script>
@endpush