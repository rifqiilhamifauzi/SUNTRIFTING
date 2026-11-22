<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1C1C1C;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .brand {
            font-size: 18px;
            font-weight: bold;
            color: #1C1C1C;
            letter-spacing: 2px;
        }
        .subtitle {
            font-size: 11px;
            color: #666666;
            margin-top: 3px;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            color: #1C1C1C;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .summary-box {
            background-color: #F8F9FA;
            border: 1px solid #E5E7EB;
            padding: 10px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            text-align: center;
        }
        .summary-table td {
            padding: 5px;
        }
        .summary-val {
            font-size: 13px;
            font-weight: bold;
            color: #1C1C1C;
            margin-top: 3px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .data-table th, .data-table td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #1C1C1C;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        .data-table tr:nth-child(even) {
            background-color: #FAFAFA;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: Courier, monospace;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SUNVALLEY THRIFT CONSIGNMENT</div>
        <div class="subtitle">Platform Konsinyasi Fesyen Vintage & Preloved Curated 1-of-1</div>
        <div class="report-title">{{ $title }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="50%">
                <strong>Periode Laporan:</strong> {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}<br>
                <strong>Mitra Supplier:</strong> {{ $supplier ? $supplier->name : 'Semua Supplier Konsinyasi' }}
            </td>
            <td width="50%" class="text-right">
                <strong>Dicetak Pada:</strong> {{ $generated_at }} WIB<br>
                <strong>Dicetak Oleh:</strong> Administrator Suntrifting
            </td>
        </tr>
    </table>

    <!-- Financial Summary Box -->
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td width="25%">
                    <span style="color: #666; font-size: 9px; text-transform: uppercase;">Total Omzet Penjualan</span>
                    <div class="summary-val">Rp{{ number_format($metrics['total_sales'], 0, ',', '.') }}</div>
                </td>
                <td width="25%">
                    <span style="color: #16A34A; font-size: 9px; text-transform: uppercase;">Komisi Admin (10%)</span>
                    <div class="summary-val" style="color: #16A34A;">Rp{{ number_format($metrics['total_admin_profit'], 0, ',', '.') }}</div>
                </td>
                <td width="25%">
                    <span style="color: #666; font-size: 9px; text-transform: uppercase;">Hak Supplier (90%)</span>
                    <div class="summary-val">Rp{{ number_format($metrics['total_supplier_profit'], 0, ',', '.') }}</div>
                </td>
                <td width="25%">
                    <span style="color: #9333EA; font-size: 9px; text-transform: uppercase;">Dana Dicairkan</span>
                    <div class="summary-val" style="color: #9333EA;">Rp{{ number_format($metrics['total_supplier_paid'], 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">No. Order</th>
                <th width="25%">Barang Thrift</th>
                <th width="18%">Supplier</th>
                <th width="12%" class="text-right">Harga Jual</th>
                <th width="12%" class="text-right">Komisi Admin (10%)</th>
                <th width="13%" class="text-right">Hak Supplier (90%)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($records as $r)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="font-mono">{{ $r->order ? $r->order->order_number : '-' }}</td>
                    <td>{{ $r->product ? $r->product->name : '-' }}</td>
                    <td>{{ $r->supplier ? $r->supplier->name : '-' }}</td>
                    <td class="text-right font-mono">Rp{{ number_format($r->selling_price, 0, ',', '.') }}</td>
                    <td class="text-right font-mono" style="color: #16A34A;">Rp{{ number_format($r->admin_amount, 0, ',', '.') }}</td>
                    <td class="text-right font-mono font-bold">Rp{{ number_format($r->supplier_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada transaksi penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Area -->
    <table class="footer">
        <tr>
            <td width="65%">
                <p style="font-size: 9px; color: #777;">
                    * Dokumen laporan ini dihasilkan otomatis oleh sistem Suntrifting PWA.<br>
                    * Pembagian hasil berdasarkan snapshot kesepakatan konsinyasi saat checkout.
                </p>
            </td>
            <td width="35%" class="text-center">
                <p>Jakarta, {{ date('d F Y') }}<br><strong>Pengelola Suntrifting</strong></p>
                <div style="height: 50px;"></div>
                <p>( _______________________ )<br>Finance & Operations</p>
            </td>
        </tr>
    </table>
</body>
</html>

