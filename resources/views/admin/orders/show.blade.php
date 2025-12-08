@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Detail Pesanan #{{ $order->order_id }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Info Pesanan -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">Informasi Pesanan</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Order ID</p>
                            <p class="fw-semibold text-dark">#{{ $order->order_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Tanggal Order</p>
                            <p class="fw-semibold text-dark">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Status Order</p>
                            <p>
                                @if($order->status == 'completed')
                                    <span class="badge rounded-pill bg-success">Selesai</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge rounded-pill bg-secondary">Dibatalkan</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Refund</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Status Pembayaran</p>
                            <p>
                                @if($order->payment_status == 'paid')
                                    <span class="badge rounded-pill bg-success">Lunas</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Gagal</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Pesanan -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">Item Pesanan</h5>
                    
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>PRODUK</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end">HARGA</th>
                                    <th class="text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product && $item->product->product_image ? asset($item->product->product_image) : 'https://via.placeholder.com/60' }}" 
                                                 alt="{{ $item->product ? $item->product->product_name : 'Deleted Product' }}"
                                                 class="rounded-3 me-3"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    {{ $item->product ? $item->product->product_name : 'Product Deleted (ID: ' . $item->product_id . ')' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center text-dark">{{ $item->quantity }}</td>
                                    <td class="text-end text-dark">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold text-dark">Rp {{ number_format($item->item_total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <div class="row mb-2">
                            <div class="col text-end text-muted">Subtotal:</div>
                            <div class="col-auto fw-semibold text-dark" style="min-width: 150px;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col text-end text-muted">Ongkir:</div>
                            <div class="col-auto fw-semibold text-dark" style="min-width: 150px;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                        </div>
                        <div class="row">
                            <div class="col text-end">
                                <h5 class="mb-0 fw-bold">Total:</h5>
                            </div>
                            <div class="col-auto" style="min-width: 150px;">
                                <h5 class="mb-0 fw-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Pelanggan & Pengiriman -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">Info Pelanggan</h5>
                    
                    <div class="mb-3">
                        <p class="mb-1 text-muted small">Nama</p>
                        <p class="fw-semibold text-dark mb-0">{{ $order->first_name }} {{ $order->last_name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1 text-muted small">Email</p>
                        <p class="fw-semibold text-dark mb-0">{{ $order->email }}</p>
                    </div>
                    
                    <div>
                        <p class="mb-1 text-muted small">Telepon</p>
                        <p class="fw-semibold text-dark mb-0">{{ $order->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">Alamat Pengiriman</h5>
                    
                    <p class="mb-1 text-dark">{{ $order->shipping_address }}</p>
                    <p class="mb-1 text-dark">{{ $order->city }}, {{ $order->postcode }}</p>
                    <p class="mb-0 text-dark">{{ $order->country ?? 'Indonesia' }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">Metode Pembayaran</h5>
                    
                    <span class="badge rounded-pill bg-info text-dark" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.card-body {
    padding: 1.5rem;
}

.table thead th {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #6c757d;
    border-bottom: 2px solid #e9ecef;
    padding: 1rem 0.75rem;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f5;
}

.badge.rounded-pill {
    padding: 0.35rem 0.85rem;
    font-weight: 500;
    font-size: 0.75rem;
}

.btn.rounded-3 {
    border-radius: 8px !important;
    padding: 0.5rem 1rem;
}

.rounded-3 {
    border-radius: 8px !important;
}

h5.fw-semibold {
    font-size: 1.1rem;
}

.small {
    font-size: 0.813rem;
}
</style>
@endsection