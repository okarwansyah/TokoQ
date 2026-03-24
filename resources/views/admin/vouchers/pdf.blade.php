<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0.5cm;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
        }
        .coupon-cell {
            width: 25%; /* 4 columns */
            height: 5.6cm; /* Approx (29.7 - 2cm margins) / 5 rows - small gap */
            padding: 5px;
            vertical-align: top;
        }
        .coupon {
            border: 1px dashed #10b981;
            border-radius: 8px;
            padding: 10px;
            height: 100%;
            text-align: center;
            position: relative;
            background: #fdfdfd;
        }
        .brand {
            font-size: 12px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 5px;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto 5px;
        }
        .code {
            font-family: 'Courier', monospace;
            font-weight: bold;
            font-size: 14px;
            background: #f1f5f9;
            padding: 2px 5px;
            border-radius: 4px;
            display: inline-block;
        }
        .instruction {
            font-size: 8px;
            color: #64748b;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <table class="grid">
        @foreach($vouchers->chunk(4) as $chunk)
        <tr>
            @foreach($chunk as $voucher)
            <td class="coupon-cell">
                <div class="coupon">
                    <div class="brand">GrosirKita</div>
                    <div class="qr-code">
                        {{-- Laravel-dompdf limitation: PHP QR Code or simple SVG/Img is better --}}
                        {{-- We'll use a placeholder for now or a simple data URI if we had a generator --}}
                        {{-- Since we use qrcode.js on frontend, we might need a PHP alternative or just code --}}
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('home', ['code' => $voucher->code])) }}" width="80" height="80">
                    </div>
                    <div class="code">{{ $voucher->code }}</div>
                    <div class="instruction">Scan QR atau masukkan kode di GrosirKita.com</div>
                </div>
            </td>
            @endforeach
            {{-- Fill empty cells in the last row if needed --}}
            @for ($i = count($chunk); $i < 4; $i++)
                <td class="coupon-cell"></td>
            @endfor
        </tr>
        @endforeach
    </table>
</body>
</html>
