<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController; // FIX: Mengatasi Class App\Http\Controllers\Controller not found

class AdminController extends BaseController 
{
    // Fungsi pembantu untuk memformat Rupiah
    private function formatRupiah($value)
    {
        return 'Rp' . number_format($value, 0, ',', '.');
    }

    // Fungsi untuk menghitung persentase pertumbuhan
    private function calculateGrowth($current, $previous)
    {
        if ($previous > 0) {
            $growthValue = (($current - $previous) / $previous) * 100;
            return ($growthValue >= 0 ? '+' : '') . round($growthValue) . '%';
        } else {
            // Logika Zero-Base Growth: Jika sebelumnya nol, dan sekarang ada, anggap +100%
            return $current > 0 ? '+100%' : '0%';
        }
    }
    
    // FUNGSI PROFIT BULANAN (Menghitung Laba Kotor untuk Chart Dashboard)
    private function getMonthlyProfit($year, $monthCount = 7)
    {
        $monthlyProfitData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->select(
                DB::raw('MONTH(orders.order_date) as month'),
                // FORMULA LABA KOTOR: item_total - (unit_cost * quantity)
                DB::raw('SUM(order_items.item_total - (order_items.unit_cost * order_items.quantity)) as profit')
            )
            ->where('orders.status', 'completed')
            ->whereYear('orders.order_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $profits = [];
        $labels = [];
        $startMonth = Carbon::now()->subMonths($monthCount - 1)->startOfMonth();
        
        for ($i = 0; $i < $monthCount; $i++) {
            $currentMonth = $startMonth->copy()->addMonths($i);
            $monthIndex = $currentMonth->month;
            
            $labels[] = $currentMonth->shortLocaleMonth; 
            $profits[] = $monthlyProfitData->get($monthIndex)->profit ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $profits
        ];
    }
    
    // FUNGSI UNTUK AMBIL DATA CHART BULANAN (Total Orders)
    private function getMonthlyOrders($year, $monthCount = 7)
    {
        $monthlyData = Order::select(
                DB::raw('MONTH(order_date) as month'),
                DB::raw('COUNT(order_id) as total_orders')
            )
            ->where('status', 'completed')
            ->whereYear('order_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $orders = [];
        $startMonth = Carbon::now()->subMonths($monthCount - 1)->startOfMonth();
        
        for ($i = 0; $i < $monthCount; $i++) {
            $currentMonth = $startMonth->copy()->addMonths($i);
            $monthIndex = $currentMonth->month;
            $orders[] = $monthlyData->get($monthIndex)->total_orders ?? 0;
        }

        return $orders;
    }


    public function index()
    {
        // --- INISIALISASI KE DEFAULT/NOL ---
        $user = Auth::user();
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->subYear()->year;
        
        $totalVisitors = 0; $formattedSales = '0'; $formattedRefunds = '0'; $formattedEarnings = 'Rp0';
        $salesGrowth = '0%'; $refundsGrowth = '0%'; $earningsGrowth = '0%'; $conversionRate = 0;
        $marketingData = ['google_ads' => 0, 'referral' => 0]; 
        $paymentsLast7Days = 0; $annualConversionRate = 0;
        $topProducts = []; $recentTransactions = []; 
        $chartData = ['labels' => ['Jan'], 'datasets' => [['label' => 'Profit', 'data' => [0]], ['label' => 'Orders', 'data' => [0]]]]; 
        $paymentMethodDistribution = []; 
        // --------------------------------------------------

        try {
            $user = Auth::user();
            
            // ===================================================
            // 1. DATA STATISTIK UTAMA (Kartu Hijau/Kuning) - Real
            // ===================================================
            $totalVisitors = User::count();
            $totalSales = Order::where('status', 'completed')->count(); // Total Penjualan
            $formattedSales = number_format($totalSales);
            $totalRefunds = Order::where('status', 'refunded')->count(); // Total Pengembalian
            $formattedRefunds = number_format($totalRefunds);
            $totalEarningsValue = Order::where('status', 'completed')->sum('total_amount');
            $formattedEarnings = $this->formatRupiah($totalEarningsValue); // Total Pemasukan (Gross Revenue)
            
            $totalBuyers = DB::table('orders')->where('status', 'completed')->distinct('customer_id')->count('customer_id');
            $conversionRate = $totalVisitors > 0 ? round(($totalBuyers / $totalVisitors) * 100, 2) : 0;
            
            // ===================================================
            // 2. PERHITUNGAN PERTUMBUHAN (PROFIT/MoM) - Real
            // ===================================================
            $currentMonth = Carbon::now()->month;
            $previousMonth = Carbon::now()->subMonth()->month;

            // Laba Bulan Ini vs Laba Bulan Lalu (MoM Growth untuk kartu Pemasukan)
            $profitCurrentMonth = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                ->where('orders.status', 'completed')
                ->whereMonth('orders.order_date', $currentMonth)
                ->whereYear('orders.order_date', $currentYear)
                ->sum(DB::raw('order_items.item_total - (order_items.unit_cost * order_items.quantity)'));

            $profitPreviousMonth = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                ->where('orders.status', 'completed')
                ->whereMonth('orders.order_date', $previousMonth)
                ->whereYear('orders.order_date', $currentYear)
                ->sum(DB::raw('order_items.item_total - (order_items.unit_cost * order_items.quantity)'));
            
            $earningsGrowth = $this->calculateGrowth($profitCurrentMonth, $profitPreviousMonth); // MoM Profit Growth

            $salesCurrentMonth = Order::where('status', 'completed')->whereMonth('order_date', $currentMonth)->whereYear('order_date', $currentYear)->count();
            $salesPreviousMonth = Order::where('status', 'completed')->whereMonth('order_date', $previousMonth)->whereYear('order_date', $currentYear)->count();
            $salesGrowth = $this->calculateGrowth($salesCurrentMonth, $salesPreviousMonth);
            $refundsCurrentMonth = Order::where('status', 'refunded')->whereMonth('order_date', $currentMonth)->whereYear('order_date', $currentYear)->count();
            $refundsPreviousMonth = Order::where('status', 'refunded')->whereMonth('order_date', $previousMonth)->whereYear('order_date', $currentYear)->count();
            $refundsGrowth = $this->calculateGrowth($refundsCurrentMonth, $refundsPreviousMonth);

            // ===================================================
            // 3. DATA KEUNTUNGAN TAHUNAN (Laba Kotor) - Real
            // ===================================================
            
            $profitThisYearValue = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                ->where('orders.status', 'completed')
                ->whereYear('orders.order_date', $currentYear)
                ->sum(DB::raw('order_items.item_total - (order_items.unit_cost * order_items.quantity)'));

            $profitLastYearValue = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                ->where('orders.status', 'completed')
                ->whereYear('orders.order_date', $previousYear)
                ->sum(DB::raw('order_items.item_total - (order_items.unit_cost * order_items.quantity)'));

            $marketingData = [
                'google_ads' => $profitThisYearValue, // Keuntungan Tahun Ini (Laba Kotor)
                'referral' => $profitLastYearValue,  // Keuntungan Tahun Lalu (Laba Kotor)
            ];
            
            // ===================================================
            // 4. DISTRIBUSI PEMBAYARAN - Real
            // ===================================================
            $paymentDistributionData = DB::table('orders')
                ->select('payment_method', DB::raw('SUM(total_amount) as total_revenue'))
                ->where('status', 'completed')
                ->groupBy('payment_method')
                ->get();

            $totalRevenueCompleted = $paymentDistributionData->sum('total_revenue');
            
            $paymentMethodDistribution = []; 
            
            foreach ($paymentDistributionData as $payment) {
                $percentage = $totalRevenueCompleted > 0 ? round(($payment->total_revenue / $totalRevenueCompleted) * 100) : 0;
                
                $paymentMethodDistribution[] = [
                    'name' => $payment->payment_method,
                    'percentage' => $percentage,
                    'revenue' => $payment->total_revenue,
                ];
            }

            usort($paymentMethodDistribution, function($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });


            // ===================================================
            // 5. DATA CHART LINE (PROFIT) & 6. DATA LAINNYA - Real
            // ===================================================
            $profitChart = $this->getMonthlyProfit($currentYear); 
            $ordersChart = $this->getMonthlyOrders($currentYear);

            $chartData = [
                'labels' => $profitChart['labels'],
                'datasets' => [
                    ['label' => 'Total Profit (Rupiah)', 'data' => $profitChart['data']],
                    ['label' => 'Total Orders', 'data' => $ordersChart]
                ]
            ];
            
            // Payments Last 7 Days: total profit 7 hari terakhir
            $paymentsLast7Days = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                ->where('orders.status', 'completed')
                ->where('orders.order_date', '>=', Carbon::now()->subDays(7))
                ->sum(DB::raw('order_items.item_total - (order_items.unit_cost * order_items.quantity)'));

            $annualBuyers = DB::table('orders')->where('status', 'completed')->whereYear('order_date', $currentYear)->distinct('customer_id')->count('customer_id');
            $annualVisitors = User::whereYear('created_at', $currentYear)->count();
            $annualConversionRate = $annualVisitors > 0 ? round(($annualBuyers / $annualVisitors) * 100, 1) : 0; 
            
            // Recent Transactions (Profit per transaksi)
            $recentTransactions = [];
            $recentOrders = Order::whereIn('status', ['completed', 'pending', 'refunded'])->orderBy('order_date', 'desc')->take(3)->get();
                
            foreach ($recentOrders as $order) {
                $profit = 0;
                if ($order->status == 'completed' || $order->status == 'refunded') {
                    $profitItems = DB::table('order_items')
                        ->where('order_id', $order->order_id)
                        ->sum(DB::raw('item_total - (unit_cost * quantity)'));
                    
                    $profit = $order->status == 'refunded' ? -$profitItems : $profitItems;
                }
                $type = $order->status == 'completed' ? 'Payment' : ($order->status == 'refunded' ? 'Refund' : 'Pending');
                
                $recentTransactions[] = [
                    'type' => $type,
                    'description' => 'Order #' . ($order->order_id ?? $order->id) . ' (' . ucfirst($order->status) . ')',
                    'amount' => $profit, // Mengirimkan PROFIT
                    'time' => Carbon::parse($order->order_date)->diffForHumans()
                ];
            }
            
            if (empty($recentTransactions)) {
                $recentTransactions = [
                    ['type' => 'Empty', 'description' => 'No Recent Transactions', 'amount' => 0, 'time' => 'now']
                ];
            }
            
            // Top Performing Products (Laba Kotor)
            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                ->select(
                    'products.product_name as name',
                    'products.category as category', 
                    DB::raw('SUM(order_items.quantity) as sales'), 
                    DB::raw('SUM(order_items.item_total - (order_items.unit_cost * order_items.quantity)) as earnings') // Laba Kotor
                )
                ->where('orders.status', 'completed')
                ->groupBy('products.product_name', 'products.category')
                ->orderBy('sales', 'desc')
                ->take(4)
                ->get()
                ->map(fn($item) => (array)$item) 
                ->toArray();
            
            if (empty($topProducts)) {
                 $topProducts = [
                    ['name' => 'No Product', 'category' => 'N/A', 'sales' => 0, 'earnings' => 0],
                ];
            }


        } catch (\Exception $e) {
            // Jika ada error DB, set semua ke nol/default minimal
            $user = Auth::user();
            $totalVisitors = 0; $formattedSales = '0'; $formattedRefunds = '0'; $formattedEarnings = 'Rp0';
            $salesGrowth = '0%'; $refundsGrowth = '0%'; $earningsGrowth = '0%'; $conversionRate = 0;
            $marketingData = ['google_ads' => 0, 'referral' => 0]; 
            $paymentsLast7Days = 0; $annualConversionRate = 0;
            $topProducts = [['name' => 'Error', 'category' => 'Error', 'sales' => 0, 'earnings' => 0]];
            $recentTransactions = [['type' => 'Error', 'description' => 'Database Error', 'amount' => 0, 'time' => 'now']];
            $chartData = ['labels' => ['Jan'], 'datasets' => [['label' => 'Profit', 'data' => [0]], ['label' => 'Orders', 'data' => [0]]]];
            $paymentMethodDistribution = [['name' => 'Error', 'percentage' => 50], ['name' => 'Error', 'percentage' => 50]]; // Fallback for payment
            
            // \Log::error('Dashboard Load Error: ' . $e->getMessage());
        }
        
        return view('admin.dashboard', compact(
            'user', 'totalVisitors', 'formattedSales', 'formattedRefunds', 'formattedEarnings', 
            'salesGrowth', 'refundsGrowth', 'earningsGrowth', 'conversionRate',
            'marketingData', 'paymentsLast7Days', 'annualConversionRate',
            'topProducts', 'recentTransactions', 'chartData',
            'paymentMethodDistribution'
        ));
    }
}