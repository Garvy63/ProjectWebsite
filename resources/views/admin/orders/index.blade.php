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
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
                                    <span class="badge rounded-pill" style="background-color: #28a745; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Selesai</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge rounded-pill" style="background-color: #ffc107; color: #000; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Pending</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge rounded-pill" style="background-color: #6c757d; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Dibatalkan</span>
                                @elseif($order->status == 'refunded')
                                    <span class="badge rounded-pill" style="background-color: #dc3545; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Refund</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge rounded-pill" style="background-color: #28a745; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Lunas</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge rounded-pill" style="background-color: #ffc107; color: #000; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Pending</span>
                                @else
                                    <span class="badge rounded-pill" style="background-color: #dc3545; color: white; padding: 0.35rem 0.75rem; font-size: 0.75rem;">Gagal</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill" style="background-color: #17a2b8; color: #000; padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                    {{ ucfirst($order->payment_method ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                                       class="btn btn-sm btn-primary" style="font-size: 0.875rem;">
                                        Detail
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown" style="font-size: 0.875rem;">
                                            Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="dropdown-item">Pending</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item">Selesai</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item">Batal</button>
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
    padding: 0.375rem 0.75rem;
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
</style>
@endsection