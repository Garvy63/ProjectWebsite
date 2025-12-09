@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <!-- Header -->
    <div class="d-flex align-items-start mb-4">
        <div class="border-start border-danger border-4 ps-3" style="border-width: 4px !important;">
            <h4 class="mb-1 fw-bold">Kelola Pesanan</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pesanan</li>
                </ol>
            </nav>
        </div>
        
        <!-- TOMBOL CREATE DI KANAN -->
        <div class="ms-auto">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Buat Pesanan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Daftar Pesanan -->
    <div class="card border-0 shadow-sm" style="border-radius: 0;">
        <div class="card-body p-0">
            <div class="p-4 border-bottom bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Daftar Pesanan</h5>
                    <span class="text-muted">Total: <strong>{{ $orders->total() }}</strong> pesanan</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">NO</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">ID ORDER</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">PELANGGAN</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">TANGGAL</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">TOTAL</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">STATUS ORDER</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">STATUS BAYAR</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">METODE BAYAR</th>
                            <th class="text-muted fw-semibold" style="font-size: 0.75rem;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>
                                <span class="text-primary fw-semibold">#{{ $order->order_id }}</span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $order->first_name }} {{ $order->last_name }}</div>
                                    <small class="text-muted">{{ $order->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $order->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ $order->items->count() }} item</small>
                            </td>
                            <td>
                                @if($order->status == 'completed')
                                    <span class="badge rounded-pill bg-success">Selesai</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge rounded-pill bg-secondary">Dibatalkan</span>
                                @elseif($order->status == 'refunded')
                                    <span class="badge rounded-pill bg-danger">Refund</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge rounded-pill bg-success">Lunas</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="badge rounded-pill bg-danger">Gagal</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary">{{ $order->payment_status }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-info text-dark">
                                    {{ ucfirst($order->payment_method ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <!-- VIEW -->
                                    <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                                       class="btn btn-sm btn-primary" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    
                                    <!-- EDIT -->
                                    <a href="{{ route('admin.orders.edit', $order->order_id) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    
                                    
                                    <!-- STATUS ORDER DROPDOWN -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown" title="Ubah Status Order">
                                            <i class="ti ti-settings"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-warning text-dark me-2">●</span>Pending
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-success me-2">●</span>Selesai
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-secondary me-2">●</span>Batal
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="refunded">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-danger me-2">●</span>Refund
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- STATUS BAYAR DROPDOWN -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-info dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown" title="Ubah Status Pembayaran">
                                            <i class="ti ti-credit-card"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('admin.orders.updatePaymentStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="pending">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-warning text-dark me-2">●</span>Pending
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updatePaymentStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="paid">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-success me-2">●</span>Lunas
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updatePaymentStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="failed">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-danger me-2">●</span>Gagal
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ti ti-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada pesanan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="d-flex justify-content-center align-items-center py-3 border-top bg-white">
                <div class="text-muted small me-3">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($orders->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" style="padding: 0.4rem 0.75rem;">
                                    <i class="ti ti-chevron-left" style="font-size: 1rem;"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->previousPageUrl() }}" style="padding: 0.4rem 0.75rem;">
                                    <i class="ti ti-chevron-left" style="font-size: 1rem;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            @if ($page == $orders->currentPage())
                                <li class="page-item active">
                                    <span class="page-link" style="padding: 0.4rem 0.75rem;">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}" style="padding: 0.4rem 0.75rem;">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($orders->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $orders->nextPageUrl() }}" style="padding: 0.4rem 0.75rem;">
                                    <i class="ti ti-chevron-right" style="font-size: 1rem;"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" style="padding: 0.4rem 0.75rem;">
                                    <i class="ti ti-chevron-right" style="font-size: 1rem;"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #e9ecef;
}

.table thead th {
    text-transform: uppercase;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
    font-weight: 600;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-sm {
    padding: 0.375rem 0.5rem;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.pagination .page-link {
    border: 1px solid #dee2e6;
    color: #495057;
    margin: 0 2px;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
}

.pagination .page-item.disabled .page-link {
    background-color: #fff;
    border-color: #dee2e6;
    color: #6c757d;
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteOrderModal'));
    const deleteOrderForm = document.getElementById('deleteOrderForm');
    const orderNumberText = document.getElementById('orderNumberText');
    
    // Handle tombol hapus
    document.querySelectorAll('.delete-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const orderNumber = this.getAttribute('data-order-number');
            
            // Update teks modal
            orderNumberText.textContent = orderNumber;
            
            // Update action form - FIXED
            deleteOrderForm.action = `/admin/orders/${orderId}`;
            
            // Tampilkan modal
            deleteModal.show();
        });
    });
    
    // Konfirmasi sebelum update status order
    const statusForms = document.querySelectorAll('form[action*="updateStatus"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin mengubah status pesanan?')) {
                e.preventDefault();
            }
        });
    });
    
    // Konfirmasi sebelum update status pembayaran
    const paymentStatusForms = document.querySelectorAll('form[action*="updatePaymentStatus"]');
    paymentStatusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin mengubah status pembayaran pesanan?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection
@endsection