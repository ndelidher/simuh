@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#718096;margin-bottom:10px;">
        <a href="{{ route('user.index') }}" style="color:#3B6D11;text-decoration:none;">Manajemen User</a>
        <span>›</span>
        <span>Edit User</span>
    </div>
    <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Edit User — {{ $user->name }}</h1>
    <p style="font-size:13px;color:#718096;">Role: {{ $user->role_label }}</p>
</div>

<form method="POST" action="{{ route('user.update', $user) }}">
@csrf @method('PUT')

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;padding:20px;margin-bottom:16px;">
    <div style="font-size:14px;font-weight:500;color:#1C4A2A;margin-bottom:16px;padding-bottom:8px;border-bottom:0.5px solid #eee;">
        Informasi Akun
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div style="grid-column:span 2;">
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Nama Lengkap <span style="color:red;">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            @error('name')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Email <span style="color:red;">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;">
            @error('email')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">No. Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="08xx-xxxx-xxxx">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Password Baru <span style="color:#a0aec0;">(kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Minimal 8 karakter">
            @error('password')<span style="font-size:11px;color:red;">{{ $message }}</span>@enderror
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:4px;">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                style="width:100%;height:36px;border:0.5px solid #ccc;border-radius:8px;padding:0 10px;font-size:13px;"
                placeholder="Ulangi password baru">
        </div>
        <div>
            <label style="font-size:12px;color:#718096;display:block;margin-bottom:8px;">Status Akun</label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $user->aktif) ? 'checked' : '' }}
                    style="width:16px;height:16px;">
                <label for="aktif" style="font-size:13px;color:#4a5568;">Akun aktif</label>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;">
    <button type="submit"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-weight:500;cursor:pointer;">
        Simpan Perubahan
    </button>
    <a href="{{ route('user.index') }}"
        style="border:0.5px solid #ccc;background:transparent;border-radius:8px;padding:10px 20px;font-size:14px;color:#718096;text-decoration:none;">
        Batal
    </a>
</div>
</form>
@endsection