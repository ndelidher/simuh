<@extends('layouts.app')
@section('title', 'Tambah User')

@section('content')
<div style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:10px;">
        <a href="{{ route('user.index') }}" style="color:#3B6D11;text-decoration:none;">Manajemen User</a>
        <span>›</span>
        <span>Tambah User</span>
    </div>
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Tambah User Baru</h1>
    <p style="font-size:13px;color:#718096;">Buat akun untuk admin di bawah cakupan wilayah Anda</p>
</div>

<form method="POST" action="{{ route('user.store') }}">
@csrf

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        Informasi Akun
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Role <span style="color:red;">*</span></label>
            <select name="role" required onchange="this.form.submit()"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih Role --</option>
                @foreach($allowedRoles as $r)
                <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>
                    {{ match($r) {
                        'admin_pp'     => 'Admin PP',
                        'admin_pwm'    => 'Admin PWM',
                        'admin_pdm'    => 'Admin PDM',
                        'admin_pcm'    => 'Admin PCM',
                        'admin_prm'    => 'Admin PRM',
                        'admin_masjid' => 'Admin Masjid',
                        default        => $r
                    } }}
                </option>
                @endforeach
            </select>
            @error('role')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">No. Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon') }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="08xx-xxxx-xxxx">
        </div>

        {{-- Scope wilayah sesuai role --}}
        @if(old('role') == 'admin_masjid')
        <div style="grid-column:span 2;">
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Masjid yang dikelola <span style="color:red;">*</span></label>
            <select name="masjid_id" style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 8px;font-size:13px;">
                <option value="">-- Pilih Masjid --</option>
                @foreach($masjidList as $m)
                <option value="{{ $m->id }}" {{ old('masjid_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama Lengkap <span style="color:red;">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Nama lengkap">
            @error('name')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Username <span style="color:red;">*</span></label>
            <input type="text" name="username" value="{{ old('username') }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Minimal 5 karakter">
            @error('username')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div style="grid-column:span 2;">
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Email <span style="color:red;">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="email@domain.com">
            @error('email')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Password <span style="color:red;">*</span></label>
            <input type="password" name="password" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Minimal 8 karakter">
            @error('password')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Konfirmasi Password <span style="color:red;">*</span></label>
            <input type="password" name="password_confirmation" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Ulangi password">
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;">
    <button type="submit"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
        Simpan User
    </button>
    <a href="{{ route('user.index') }}"
        style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">
        Batal
    </a>
</div>
</form>
@endsectiondiv>
    <!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
</div>
