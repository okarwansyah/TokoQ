@extends('layouts.app')

@section('content')
<div class="glass" style="width: 100%; max-width: 600px; padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.vouchers.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">&larr; Kembali ke Daftar</a>
        <h1 style="margin: 1rem 0 0 0; font-size: 1.5rem;">Edit Voucher: <code style="color: var(--primary);">{{ $voucher->code }}</code></h1>
    </div>

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
            <label style="color: var(--text-main); font-weight: 700;">STATUS VOUCHER:</label>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <label class="switch">
                    <input type="checkbox" name="is_active" value="1" {{ $voucher->is_active ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
                <span style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Aktif (Dapat digunakan oleh user)</span>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 700;">GAMBAR HADIAH</label>
            @if($voucher->image)
                <div style="margin-bottom: 1rem;">
                    <img src="{{ asset('storage/' . $voucher->image) }}" alt="Preview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                </div>
            @endif
            <input type="file" name="image" style="width: 100%; padding: 0.75rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.625rem; color: var(--text-main);">
            @error('image') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 700;">DESKRIPSI HADIAH</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.875rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.625rem; color: var(--text-main); resize: none; outline: none; transition: border-color 0.2s;" placeholder="Contoh: Selamat! Anda mendapatkan voucher belanja Rp 50.000">{{ old('description', $voucher->description) }}</textarea>
            @error('description') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 700;">LINK KLAIM (SHOPEE)</label>
            <input type="url" name="shopee_link" value="{{ old('shopee_link', $voucher->shopee_link) }}" style="width: 100%; padding: 0.875rem; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.625rem; color: var(--text-main); outline: none; transition: border-color 0.2s;" placeholder="https://shopee.co.id/...">
            @error('shopee_link') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 1rem; padding: 1rem;">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
