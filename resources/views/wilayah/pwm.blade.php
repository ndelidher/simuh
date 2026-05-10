@extends('layouts.app')
@section('title', 'Data PWM')

@section('content')
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
    <div>
        <h1 style="font-size:20px;font-weight:500;margin-bottom:4px;">Data PWM</h1>
        <p style="font-size:13px;color:#718096;">Pimpinan Wilayah Muhammadiyah · {{ $data->total() }} wilayah</p>
    </div>
    <a href="{{ route('wilayah.import') }}"
        style="background:#1C4A2A;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:500;text-decoration:none;">
        ⬆ Import Excel
    </a>
</div>

<div style="background:#fff;border:0.5px solid #dde8d5;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:40px;">#</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;">Nama PWM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:120px;">Kode</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:left;width:100px;">Provinsi</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Jml PDM</th>
                <th style="background:#EAF3DE;color:#27500A;font-size:12px;font-weight:500;padding:10px 12px;border-bottom:0.5px solid #dde8d5;text-align:center;width:80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr style="border-bottom:0.5px solid #f0f4ec;">
                <td style="padding:9px 12px;font-size:12px;color:#718096;">{{ $data->firstItem() + $i }}</td>
                <td style="padding:9px 12px;font-size:13px;font-weight:500;">{{ $row->nama }}</td>
                <td style="padding:9px 12px;font-size:12px;color:#718096;">{{ $row->kode }}</td>
                <td style="padding:9px 12px;font-size:12px;color:#718096;">{{ $row->provinsi ?? '—' }}</td>
                <td style="padding:9px 12px;font-size:12px;text-align:center;">{{ $row->pdm_list_count }}</td>
                <td style="padding:9px 12px;text-align:center;">
                    @if($row->aktif)
                        <span style="background:#EAF3DE;color:#27500A;font-size:10px;padding:2px 8px;border-radius:20px;">Aktif</span>
                    @else
                        <span style="background:#F7C1C1;color:#791F1F;font-size:10px;padding:2px 8px;border-radius:20px;">Non-aktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:40px;text-align:center;color:#718096;font-size:13px;">
                    Belum ada data PWM. <a href="{{ route('wilayah.import') }}" style="color:#3B6D11;">Import dari Excel</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:10px 16px;border-top:0.5px solid #dde8d5;font-size:12px;color:#718096;">
        {{ $data->links() }}
    </div>
</div>
@endsection