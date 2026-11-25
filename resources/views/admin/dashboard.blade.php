@extends('layouts.admin', ['title' => 'Admin Dashboard — SUN-THRIFTING', 'headerTitle' => 'Ringkasan & Metrik Bisnis Konsinyasi'])

@section('content')
<div class="space-y-8">
    <!-- Top Financial Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Omzet Penjualan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Omzet Penjualan</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono">
                    Rp{{ number_format($metrics['total_sales'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-400 mt-0.5">{{ $metrics['successful_orders_count'] }} transaksi lunas berhasil</p>
            </div>
        </div>

        <!-- Keuntungan Komisi Admin -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Pendapatan Admin (10%)</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="pie-chart" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="font-heading font-extrabold text-2xl text-emerald-700 font-mono">
                    Rp{{ number_format($metrics['total_admin_profit'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-400 mt-0.5">Komisi bersih platform</p>
            </div>
        </div>

        <!-- Kewajiban Supplier Belum Cair -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Kewajiban Supplier (90%)</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="font-heading font-extrabold text-2xl text-amber-700 font-mono">
                    Rp{{ number_format($metrics['unpaid_supplier_liabilities'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-400 mt-0.5">Hak supplier belum dicairkan</p>
            </div>
        </div>

        <!-- Total Dana Supplier Sudah Dicairkan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dana Sudah Dicairkan</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="font-heading font-extrabold text-2xl text-gray-900 font-mono">
                    Rp{{ number_format($metrics['total_supplier_paid'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-400 mt-0.5">Total transfer ke supplier</p>
            </div>
        </div>
    </div>

    <!-- Inventory & Quick Action Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                <i data-lucide="shirt" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Produk</p>
                <p class="font-heading font-bold text-lg text-gray-900">{{ $stats['total_products'] }} <span class="text-xs font-normal text-emerald-600">({{ $stats['available_products'] }} siap jual)</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Produk Terjual</p>
                <p class="font-heading font-bold text-lg text-gray-900">{{ $stats['sold_products'] }} item</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Mitra Supplier</p>
                <p class="font-heading font-bold text-lg text-gray-900">{{ $stats['total_suppliers'] }} supplier</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                <i data-lucide="package" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Pesanan</p>
                <p class="font-heading font-bold text-lg text-gray-900">{{ $stats['total_orders'] }} order</p>
            </div>
        </div>
    </div>

    <!-- Chart & Urgent Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sales & Profit Chart (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="font-heading font-bold text-sm text-gray-900">Tren Penjualan & Profit Admin (7 Hari Terakhir)</h3>
                    <p class="text-[11px] text-gray-400">Grafik pergerakan omzet vs komisi bersih platform</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Urgent Attention & Quick Actions (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Pending Payment Proofs -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <h4 class="font-heading font-bold text-xs text-gray-900 flex items-center">
                        <i data-lucide="bell" class="w-4 h-4 mr-1.5 text-amber-500"></i>
                        Perlu Verifikasi Segera
                    </h4>
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                        {{ $pendingPayments->count() }} menunggu
                    </span>
                </div>

                @if($pendingPayments->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-4">Semua pembayaran sudah diverifikasi.</p>
                @else
                    <div class="space-y-2.5">
                        @foreach($pendingPayments as $pay)
                            <a href="{{ route('admin.payments.index') }}" class="p-3 bg-gray-50 hover:bg-gray-100 rounded-xl block text-xs transition">
                                <div class="flex items-center justify-between font-semibold text-gray-900">
                                    <span>{{ $pay->order->order_number }}</span>
                                    <span class="text-accent font-mono font-bold">Rp{{ number_format($pay->amount, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5">Dari: {{ $pay->account_name ?: ($pay->order ? $pay->order->recipient_name : ($pay->order->user->name ?? 'Pembeli')) }} ({{ $pay->bank_name }})</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quick Action Buttons -->
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-2.5">
                <h4 class="font-heading font-bold text-xs text-gray-900 pb-2 border-b border-gray-100">Aksi Cepat</h4>

                <a href="{{ route('admin.products.create') }}" class="w-full bg-primary hover:bg-gray-800 text-white font-semibold py-2.5 px-3 rounded-xl text-xs flex items-center justify-center space-x-2 transition shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Input Barang Thrift Baru</span>
                </a>

                <a href="{{ route('admin.payouts.create') }}" class="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-2.5 px-3 rounded-xl text-xs flex items-center justify-center space-x-2 transition shadow-sm">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Proses Pencairan Supplier</span>
                </a>

                <a href="{{ route('admin.suppliers.create') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 px-3 rounded-xl text-xs flex items-center justify-center space-x-2 transition">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Tambah Mitra Supplier</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="font-heading font-bold text-sm text-gray-900">Pesanan Masuk Terbaru</h3>
                <p class="text-[11px] text-gray-400">Ringkasan transaksi konsumen real-time</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-accent hover:underline">
                Lihat Semua Pesanan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-y border-gray-100">
                    <tr>
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Status Pesanan</th>
                        <th class="py-3 px-4">Status Bayar</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-gray-900">{{ $order->order_number }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-gray-900">{{ $order->recipient_name }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $order->recipient_phone }} &bull; {{ $order->city }}</div>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{!! $order->order_status_badge !!}</td>
                            <td class="py-3 px-4">{!! $order->payment_status_badge !!}</td>
                            <td class="py-3 px-4 font-mono text-gray-500">
                                <div>{{ $order->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</div>
                                <span class="text-[10px] text-accent block font-sans">{{ $order->created_at->timezone('Asia/Jakarta')->diffForHumans() }}</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-accent hover:underline font-semibold">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400">Belum ada transaksi pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($metrics['chart_dates']) !!},
                    datasets: [
                        {
                            label: 'Omzet Penjualan (Rp)',
                            data: {!! json_encode($metrics['chart_sales']) !!},
                            borderColor: '#1C1C1C',
                            backgroundColor: 'rgba(28, 28, 28, 0.05)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            pointBackgroundColor: '#1C1C1C',
                        },
                        {
                            label: 'Keuntungan Admin 10% (Rp)',
                            data: {!! json_encode($metrics['chart_admin_profits']) !!},
                            borderColor: '#84977B',
                            backgroundColor: 'rgba(132, 151, 123, 0.1)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            pointBackgroundColor: '#84977B',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + (value / 1000).toLocaleString() + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

