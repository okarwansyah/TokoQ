<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminVoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function exportPdf()
    {
        // Ambil voucher yang aktif dan belum terpakai (atau semua yang aktif)
        $vouchers = Voucher::where('is_active', true)
                           ->where('is_claimed', false)
                           ->get();

        if ($vouchers->isEmpty()) {
            return back()->with('error', 'Tidak ada voucher aktif untuk diexport.');
        }

        $pdf = Pdf::loadView('admin.vouchers.pdf', compact('vouchers'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('vouchers-grosirkita.pdf');
    }

    public function generate()
    {
        $code = strtoupper(Str::random(8));
        
        while (Voucher::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }

        Voucher::create([
            'code' => $code,
        ]);

        return back()->with('success', 'Voucher baru berhasil dibuat: ' . $code);
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'shopee_link' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['description', 'shopee_link']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($voucher->image) {
                Storage::disk('public')->delete($voucher->image);
            }
            $data['image'] = $request->file('image')->store('vouchers', 'public');
        }

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->update(['is_active' => !$voucher->is_active]);
        return response()->json(['success' => true, 'is_active' => $voucher->is_active]);
    }
}
