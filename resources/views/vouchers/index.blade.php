@extends('layouts.app')

@section('content')
<div class="glass" style="max-width: 500px; width: 100%; padding: 3rem 2rem; text-align: center;">
    <h1 style="margin-top: 0; font-size: 2.5rem; letter-spacing: -0.025em; margin-bottom: 1rem;">Tukarkan Voucher</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Masukkan kode voucher Anda untuk melihat hadiah.</p>
    
    <div style="margin-bottom: 2rem;">
        <input type="text" id="voucher-input" placeholder="Contoh: AB12CD34" style="width: 100%; padding: 1rem 1.25rem; background: #f1f5f9; border: 1px solid var(--glass-border); border-radius: 0.75rem; color: var(--text-main); font-family: monospace; font-size: 1.25rem; text-align: center; text-transform: uppercase; outline: none; transition: border-color 0.2s;">
        <p id="error-msg" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.75rem; display: none;"></p>
    </div>

    <button id="check-btn" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; font-size: 1.125rem;">
        Tukarkan Sekarang
    </button>
</div>

<!-- Modal -->
<div id="prize-modal" class="modal-overlay">
    <div class="glass modal-content">
        <span class="close-modal">&times;</span>
        <h2 style="margin-bottom: 1.5rem; color: var(--primary);">🎉 Selamat!</h2>
        <img id="prize-image" src="" alt="Hadiah" class="prize-img">
        <p id="prize-description" style="font-size: 1.125rem; margin-bottom: 2rem;"></p>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <button id="claim-voucher-btn" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.875rem;">
                Pakai Voucher
            </button>
            <a id="shopee-link" href="#" target="_blank" class="btn btn-outline" style="width: 100%; justify-content: center; padding: 0.875rem;">
                Lihat di Shopee
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('prize-modal');
    const input = document.getElementById('voucher-input');
    const errorMsg = document.getElementById('error-msg');
    const checkBtn = document.getElementById('check-btn');
    const claimBtn = document.getElementById('claim-voucher-btn');
    let currentVoucherCode = '';

    // Auto-check voucher if code is in URL
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const code = urlParams.get('code');
        if (code) {
            input.value = code;
            checkBtn.click();
        }
    };

    checkBtn.addEventListener('click', function() {
        const code = input.value.trim();
        if(!code) return;

        checkBtn.disabled = true;
        checkBtn.innerHTML = 'Mengecek...';
        errorMsg.style.display = 'none';

        fetch('{{ route('vouchers.check') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Gagal mengecek voucher.');
            }
            return data;
        })
        .then(data => {
            currentVoucherCode = data.code;
            document.getElementById('prize-image').src = data.image;
            document.getElementById('prize-description').textContent = data.description;
            
            const shopeeBtn = document.getElementById('shopee-link');
            if (data.shopee_link) {
                shopeeBtn.href = data.shopee_link;
                shopeeBtn.style.display = 'inline-flex';
            } else {
                shopeeBtn.style.display = 'none';
            }

            modal.style.display = 'flex';
            checkBtn.disabled = false;
            checkBtn.innerHTML = 'Tukarkan Sekarang';
        })
        .catch(error => {
            errorMsg.textContent = error.message;
            errorMsg.style.display = 'block';
            checkBtn.disabled = false;
            checkBtn.innerHTML = 'Tukarkan Sekarang';
        });
    });

    claimBtn.addEventListener('click', function() {
        if(!currentVoucherCode) return;

        claimBtn.disabled = true;
        claimBtn.innerHTML = 'Memproses...';

        fetch('{{ route('vouchers.claim') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: currentVoucherCode })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Voucher berhasil digunakan!');
                location.reload();
            } else {
                alert(data.message || 'Gagal menggunakan voucher.');
                claimBtn.disabled = false;
                claimBtn.innerHTML = 'Pakai Voucher';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
            claimBtn.disabled = false;
            claimBtn.innerHTML = 'Pakai Voucher';
        });
    });

    document.querySelector('.close-modal').addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush
