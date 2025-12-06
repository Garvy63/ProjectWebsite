@extends('layouts.admin')

@section('content')

{{-- ==================== BAGIAN ATAS: 4 KARTU STATISTIK (DIPERBARUI) ==================== --}}
<div class="row">

    {{-- KARTU WELCOME (Besar, Ambil 6 kolom) --}}
    <div class="col-lg-6 mb-4">
        <div class="card overflow-hidden" style="background-color: #f7a240; border-radius: 12px;">
            <div class="row g-0">
                {{-- Bagian Kiri: Teks --}}
                <div class="col-sm-6">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold text-white">Welcome, {{ $user->name ?? 'Admin' }}!</h5>
                        <div class="d-flex align-items-center gap-4 mt-4">
                            <div class="text-center">
                                <h4 class="mb-1 fs-4 fw-bold text-white">{{ $totalVisitors ?? 0 }}</h4>
                                <p class="text-white mb-0 fs-4">Pengunjung</p>
                            </div>
                            <div class="text-center">
                                <h4 class="mb-1 fs-4 fw-bold text-white">{{ $conversionRate ?? 0 }}%</h4>
                                <p class="text-white mb-0 fs-4">Pengunjung - Pembeli</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bagian Kanan: Gambar --}}
                <div class="col-sm-6 text-center position-relative">
                    <div class="p-3 pt-4">
                        <img src="{{ asset('admin_assets/assets/images/welcome/welcome.png') }}"
                             height="130" alt="welcome.png">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- WADAH UNTUK 3 KARTU KECIL (Ambil 6 kolom) --}}
    <div class="col-lg-6">
        <div class="row">
            {{-- KARTU SALES (Hijau Muda) --}}
            <div class="col-6 col-md-4 mb-4">
                <div class="card h-100" style="background-color: #d5ff6c; border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column align-items-start">
                            {{-- Icon Circle --}}
                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                                 style="background-color: #84994F; width: 50px; height: 50px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <i class="fas fa-history fs-5 text-white"></i>
                            </div>

                            {{-- Angka & Label --}}
                            <h3 class="mb-1 fs-5 fw-bold text-dark">{{ $formattedSales ?? '0' }}</h3>
                            <p class="mb-1 fs-4 text-secondary">Penjualan</p>

                            {{-- Badge Persentase --}}
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge rounded-pill bg-success-subtle text-success fw-semibold"
                                      style="font-size: 0.75rem;">
                                    {{ $salesGrowth ?? '0%' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU REFUNDS (Kuning) --}}
            <div class="col-6 col-md-4 mb-4">
                <div class="card h-100" style="background-color: #FFE797; border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column align-items-start">
                            {{-- Icon Circle --}}
                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                                 style="background-color: #FCB53B; width: 50px; height: 50px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <i class="fas fa-redo fs-5 text-white"></i>
                            </div>

                            {{-- Angka & Label --}}
                            <h3 class="mb-1 fs-5 fw-bold text-dark">{{ $formattedRefunds ?? '0' }}</h3>
                            <p class="mb-1 fs-4 text-secondary">Pengembalian</p>

                            {{-- Badge Persentase --}}
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge rounded-pill bg-danger-subtle text-danger fw-semibold"
                                      style="font-size: 0.75rem;">
                                    {{ $refundsGrowth ?? '0%' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU EARNINGS (Hijau) --}}
            <div class="col-6 col-md-4 mb-4">
                <div class="card h-100" style="background-color: #C1DBB3; border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column align-items-start">
                            {{-- Icon Circle --}}
                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                                 style="background-color: #84af6d; width: 50px; height: 50px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                <i class="fas fa-dollar-sign fs-5 text-white"></i>
                            </div>

                            {{-- Angka & Label --}}
                            <h3 class="mb-1 fs-5 fw-bold text-dark">{{ $formattedEarnings ?? 'Rp0' }}</h3>
                            <p class="mb-1 fs-4 text-secondary">Pemasukan</p>

                            {{-- Badge Persentase (PROFIT GROWTH MoM) --}}
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge rounded-pill bg-success-subtle text-success fw-semibold"
                                      style="font-size: 0.75rem;">
                                    {{ $earningsGrowth ?? '0%' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
{{-- ==================== END BAGIAN ATAS ==================== --}}

{{-- ==================== BAGIAN BAWAH: DASHBOARD eCommerce ==================== --}}
<div class="row mt-4">

    {{-- KOLOM KIRI: Chart dan Laporan --}}
    <div class="col-lg-8">

        {{-- Marketing Report Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Keuntungan Penjualan</h5>
                </div>

                {{-- Marketing Metrics (Keuntungan Tahunan) --}}
                <div class="row mb-4">

                    {{-- Slot 1: Keuntungan Tahun Ini (Total Rupiah PROFIT) --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-chart-line text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Keuntungan Tahun Ini</h6>
                                {{-- Tampilkan Rupiah Total PROFIT TAHUN INI --}}
                                @php
                                    $profitTY = $marketingData['google_ads'] ?? 0;
                                    $colorTY = $profitTY >= 0 ? 'text-success' : 'text-danger';
                                @endphp
                                <h4 class="mb-0 fw-bold {{ $colorTY }}">
                                    {{ $profitTY >= 0 ? '+' : '' }}Rp{{ number_format(abs($profitTY), 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    {{-- Slot 2: Keuntungan Tahun Lalu (Total Rupiah PROFIT) --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-calendar-alt text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Keuntungan Tahun Lalu</h6>
                                {{-- Tampilkan Rupiah Total PROFIT TAHUN LALU --}}
                                @php
                                    $profitLY = $marketingData['referral'] ?? 0;
                                    $colorLY = $profitLY >= 0 ? 'text-success' : 'text-danger';
                                @endphp
                                <h4 class="mb-0 fw-bold {{ $colorLY }}">
                                    {{ $profitLY >= 0 ? '+' : '' }}Rp{{ number_format(abs($profitLY), 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Chart (Sudah menggunakan data Profit) --}}
                {{-- Wrapper untuk Scroll Horizontal --}}
                <div style="overflow-x: auto;">
                    <div class="chart-container" style="width: 1200px; height: 300px;">
                        <canvas id="marketingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Two Small Cards Row --}}
        <div class="row">
            {{-- Payments Card (Sekarang menunjukkan PROFIT 7 hari terakhir) --}}
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">Laba 7 Hari Terakhir</h6>
                            <span class="badge bg-light text-dark">Last 7 days</span>
                        </div>
                        {{-- Menampilkan Rupiah untuk Total PROFIT 7 Hari --}}
                        @php
                            $profit7Days = $paymentsLast7Days ?? 0;
                            $color7 = $profit7Days >= 0 ? 'text-dark' : 'text-danger';
                        @endphp
                        <h2 class="fw-bold {{ $color7 }}">{{ $profit7Days >= 0 ? '' : '-' }}Rp{{ number_format(abs($profit7Days)) }}</h2>
                        <div class="progress" style="height: 8px;">
                            @php
                                $progress = isset($paymentsLast7Days) ? min(($paymentsLast7Days / 20000000) * 100, 100) : 75;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-muted small mt-2">+18.2% from last week</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: Sidebar Info --}}
    <div class="col-lg-4">

        {{-- Payment Methods Card (FIXED REAL DATA) --}}
        @php
            // Ambil data dari Controller
            $paymentData = $paymentMethodDistribution ?? [];

            // Definisikan ikon dan warna untuk metode pembayaran (sesuai data DB kamu)
            $methodIcons = [
                'PayPal' => ['icon' => 'fab fa-paypal', 'color' => 'primary'],
                'Credit Card' => ['icon' => 'far fa-credit-card', 'color' => 'info'],
                'Dompet Digital' => ['icon' => 'fas fa-wallet', 'color' => 'warning'],
                'Cash' => ['icon' => 'fas fa-money-bill', 'color' => 'success'],
            ];

            // Ambil top 2 methods untuk display
            $topMethodsToDisplay = array_slice($paymentData, 0, 2);

            // Hitung total persentase untuk progress bar
            $totalPrimary = array_sum(array_column($paymentData, 'percentage'));

        @endphp

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Metode Pembayaran (Top 2)</h5>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    @foreach($topMethodsToDisplay as $i => $method)
                        @php
                            $details = $methodIcons[$method['name']] ?? ['icon' => 'fas fa-question', 'color' => 'secondary'];
                        @endphp
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-{{ $details['color'] }} bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px;">
                                <i class="{{ $details['icon'] }} text-{{ $details['color'] }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $method['name'] }}</h6>
                                <p class="text-muted mb-0">{{ $method['percentage'] }}%</p>
                            </div>
                        </div>
                    @endforeach
                    @if(count($topMethodsToDisplay) == 1)
                        {{-- Isi slot kosong jika hanya ada 1 data --}}
                        <div class="d-flex align-items-center">
                            <div style="width: 40px; height: 40px;"></div>
                            <div><h6 class="mb-0">N/A</h6><p class="text-muted mb-0">0%</p></div>
                        </div>
                    @endif
                </div>

                {{-- Progress Bar untuk semua metode yang ada --}}
                <div class="progress" style="height: 10px;">
                    @php
                        $processedWidth = 0;
                    @endphp
                    @foreach($paymentData as $method)
                        <div class="progress-bar bg-{{ $methodIcons[$method['name']]['color'] ?? 'secondary' }}"
                             style="width: {{ $method['percentage'] }}%"
                             role="progressbar" aria-valuenow="{{ $method['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        @php
                            $processedWidth += $method['percentage'];
                        @endphp
                    @endforeach
                    {{-- Sisanya (jika total < 100 karena pembulatan) --}}
                    @if(100 - $processedWidth > 0)
                        <div class="progress-bar bg-secondary" style="width: {{ 100 - $processedWidth }}%" role="progressbar"></div>
                    @endif
                </div>

                <small class="text-muted mt-2 d-block">
                    *Persentase dari total pendapatan order completed.
                </small>
            </div>
        </div>

        {{-- Top Performing Products (Laba Kotor) --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Top Performing Products (Laba)</h5>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Sales (Qty)</th>
                                <th>Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts ?? [] as $product)
                            <tr>
                                <td>{{ $product['name'] ?? 'Product' }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $product['category'] ?? 'Category' }}
                                    </span>
                                </td>
                                <td>{{ number_format($product['sales'] ?? 0) }}</td>
                                @php
                                    $profitProduct = $product['earnings'] ?? 0;
                                    $colorProduct = $profitProduct >= 0 ? 'text-success' : 'text-danger';
                                @endphp
                                <td class="{{ $colorProduct }} fw-bold">
                                    {{ $profitProduct >= 0 ? '+' : '' }}Rp{{ number_format($profitProduct) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Transactions (Profit/Loss) --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Laba/Rugi Transaksi Terbaru</h5>

                @foreach($recentTransactions ?? [] as $transaction)
                <div class="d-flex align-items-center mb-3">
                    @php
                        $isPositive = ($transaction['amount'] ?? 0) >= 0;
                        $iconClass = $isPositive ? 'text-success' : 'text-danger';
                        $bgClass = $isPositive ? 'bg-success' : 'bg-danger';
                        $icon = 'fas fa-exchange-alt';
                        if (($transaction['type'] ?? '') == 'Refund') $icon = 'fas fa-undo-alt';
                        if (($transaction['type'] ?? '') == 'Pending') $icon = 'fas fa-clock';
                        if (($transaction['type'] ?? '') == 'Payment') $icon = 'fas fa-money-check-alt';
                    @endphp

                    <div class="rounded-circle {{ $bgClass }}-subtle d-flex align-items-center justify-content-center me-3"
                         style="width: 50px; height: 50px;">
                        <i class="{{ $icon }} {{ $iconClass }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $transaction['type'] ?? 'Transaction' }}</h6>
                        <p class="text-muted mb-0">{{ $transaction['description'] ?? 'Description' }}</p>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0 {{ $iconClass }}">
                            {{-- Menggunakan format Rupiah lengkap dengan tanda +/- --}}
                            {{ $isPositive ? '+' : '' }}Rp{{ number_format(abs($transaction['amount'] ?? 0)) }}
                        </h5>
                        <small class="text-muted">{{ $transaction['time'] ?? 'Recently' }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('marketingChart').getContext('2d');

        // Data dari Controller
        const chartData = @json($chartData);

        const marketingChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        // Dataset 1: Total Profit (Uang)
                        label: chartData.datasets[0].label,
                        data: chartData.datasets[0].data,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        // Dataset 2: Total Orders (Count)
                        label: chartData.datasets[1].label,
                        data: chartData.datasets[1].data,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.05)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: {
                    x: { grid: { display: false } },
                    // Y-Axis Kiri (Untuk Profit)
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Profit (Rp)'
                        },
                        ticks: {
                            callback: function(value, index, ticks) {
                                // Format angka menjadi Rupiah
                                if (value >= 1000000) {
                                    return 'Rp' + (value/1000000).toLocaleString('id-ID') + ' Jt';
                                } else if (value >= 1000) {
                                    return 'Rp' + (value/1000).toLocaleString('id-ID') + ' Rb';
                                }
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    // Y-Axis Kanan (Untuk Order Count)
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Orders (Count)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
