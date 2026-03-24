<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AdminVoucherController;
use App\Http\Controllers\AdminAuthController;

// User Routes
Route::get('/', [VoucherController::class, 'index'])->name('home');
Route::post('/redeem/check', [VoucherController::class, 'check'])->name('vouchers.check');
Route::post('/redeem/claim', [VoucherController::class, 'claim'])->name('vouchers.claim');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/vouchers', [AdminVoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::post('/vouchers/generate', [AdminVoucherController::class, 'generate'])->name('admin.vouchers.generate');
    Route::get('/vouchers/export-pdf', [AdminVoucherController::class, 'exportPdf'])->name('admin.vouchers.export-pdf');
    Route::get('/vouchers/{voucher}/edit', [AdminVoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [AdminVoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::post('/vouchers/{voucher}/toggle', [AdminVoucherController::class, 'toggle'])->name('admin.vouchers.toggle');
});
