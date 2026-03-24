@extends('layouts.app')

@section('content')
<div class="glass" style="width: 100%; max-width: 1000px; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="margin: 0; font-size: 1.5rem;">Manajemen Voucher</h1>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem;">Kelola kode voucher dan hadiah Anda di sini.</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="https://github.com/davidshimjs/qrcodejs" target="_blank" style="display:none">QR Lib</a>
            <a href="{{ route('admin.vouchers.export-pdf') }}" class="btn btn-outline" style="font-size: 0.875rem; border-color: var(--primary); color: var(--primary);">
                💾 Export PDF
            </a>
            <form action="{{ route('admin.vouchers.generate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="font-size: 0.875rem;">
                    + Generate Voucher Baru
                </button>
            </form>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline" style="font-size: 0.875rem; color: #ef4444; border-color: rgba(239, 68, 68, 0.2);">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-success" style="background: #fef2f2; border-color: #fecaca; color: #b91c1c;">
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9; color: var(--text-muted); font-size: 0.875rem;">
                    <th style="padding: 1rem;">KODE</th>
                    <th style="padding: 1rem;">QR CODE</th>
                    <th style="padding: 1rem;">DESKRIPSI</th>
                    <th style="padding: 1rem;">SHOPEE LINK</th>
                    <th style="padding: 1rem;">STATUS</th>
                    <th style="padding: 1rem;">CLAIM</th>
                    <th style="padding: 1rem; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                <tr style="border-bottom: 1px solid #f8fafc;">
                    <td style="padding: 1rem;"><code style="color: var(--primary); font-weight: 700; font-size: 1rem;">{{ $voucher->code }}</code></td>
                    <td style="padding: 1rem;">
                        <div id="qrcode-{{ $voucher->code }}" class="qr-box"></div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-main); font-weight: 500;">
                            {{ $voucher->description ?: '-' }}
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--primary); font-size: 0.8rem; font-weight: 500;">
                            {{ $voucher->shopee_link ?: '-' }}
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <label class="switch">
                                <input type="checkbox" onchange="toggleVoucher('{{ $voucher->code }}', '{{ route('admin.vouchers.toggle', $voucher) }}')" {{ $voucher->is_active ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span id="status-text-{{ $voucher->code }}" style="font-size: 0.75rem; font-weight: 700; color: {{ $voucher->is_active ? '#10b981' : '#ef4444' }};">
                                {{ $voucher->is_active ? 'AKTIF' : 'OFF' }}
                            </span>
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        @if($voucher->is_claimed)
                            <span style="padding: 0.25rem 0.625rem; background: #fee2e2; color: #b91c1c; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 700;">TERPAKAI</span>
                        @else
                            <span style="padding: 0.25rem 0.625rem; background: #ecfdf5; color: #047857; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 700;">TERSSEDIA</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem; border-radius: 0.5rem; border-color: #e2e8f0;">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🎫</div>
                        Belum ada voucher.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $vouchers->links() }}
    </div>
</div>

<style>
    .qr-box img { 
        padding: 4px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        width: 50px;
        height: 50px;
    }
    .pagination { display: flex; list-style: none; gap: 0.25rem; justify-content: center; padding: 0; }
    .page-item { border-radius: 0.5rem; overflow: hidden; }
    .page-link { display: block; padding: 0.5rem 1rem; background: white; color: var(--text-main); text-decoration: none; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.875rem; }
    .page-item.active .page-link { background: var(--primary); color: white; border-color: var(--primary); }
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function toggleVoucher(code, url) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const statusText = document.getElementById(`status-text-${code}`);
                statusText.textContent = data.is_active ? 'AKTIF' : 'NON-AKTIF';
                statusText.style.color = data.is_active ? '#10b981' : '#ef4444';
            } else {
                alert('Gagal mengubah status.');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
            location.reload();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        @foreach($vouchers as $voucher)
        new QRCode(document.getElementById("qrcode-{{ $voucher->code }}"), {
            text: "{{ route('home', ['code' => $voucher->code]) }}",
            width: 60,
            height: 60,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
        @endforeach
    });
</script>
@endpush
@endsection
