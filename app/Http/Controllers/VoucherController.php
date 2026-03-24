<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return view('vouchers.index');
    }

    public function check(Request $request)
    {
        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak valid.'], 404);
        }

        if ($voucher->is_claimed) {
            return response()->json(['success' => false, 'message' => 'Voucher sudah digunakan.'], 400);
        }

        if (!$voucher->is_active) {
            return response()->json(['success' => false, 'message' => 'Voucher sedang dinonaktifkan.'], 403);
        }

        return response()->json([
            'success' => true,
            'code' => $voucher->code,
            'image' => $voucher->image ? asset('storage/' . $voucher->image) : asset('images/default-voucher.png'),
            'description' => $voucher->description ?? 'Selamat! Anda mendapatkan hadiah.',
            'shopee_link' => $voucher->shopee_link,
        ]);
    }

    public function claim(Request $request)
    {
        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.'], 404);
        }

        if ($voucher->is_claimed) {
            return response()->json(['success' => false, 'message' => 'Voucher sudah diklaim.'], 400);
        }

        $voucher->update(['is_claimed' => true]);

        return response()->json(['success' => true]);
    }
}
