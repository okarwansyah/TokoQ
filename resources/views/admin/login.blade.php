@extends('layouts.app')

@section('content')
<div class="glass" style="max-width: 400px; width: 100%; padding: 3rem 2rem; text-align: center;">
    <h1 style="margin-top: 0; font-size: 2rem; margin-bottom: 2rem;">Admin Login</h1>
    
    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div style="margin-bottom: 1.5rem; text-align: left;">
            <label style="display: block; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 600;">EMAIL</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.875rem; background: #f1f5f9; border: 1px solid var(--glass-border); border-radius: 0.625rem; color: var(--text-main); outline: none;">
            @error('email') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 2rem; text-align: left;">
            <label style="display: block; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 600;">PASSWORD</label>
            <input type="password" name="password" required style="width: 100%; padding: 0.875rem; background: #f1f5f9; border: 1px solid var(--glass-border); border-radius: 0.625rem; color: var(--text-main); outline: none;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 1rem;">
            Masuk ke Panel Admin
        </button>
    </form>
</div>
@endsection
