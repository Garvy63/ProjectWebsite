@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <!-- Header -->
    <div class="d-flex align-items-start mb-4">
        <div class="border-start border-warning border-4 ps-3" style="border-width: 4px !important;">
            <h4 class="mb-1 fw-bold">Edit Pesanan #{{ $order->order_id }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.show', $order->order_id) }}">Detail</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Edit Order -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->order_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-semibold border-bottom pb-2">Informasi Pelanggan</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Pelanggan</label>
                            <input type="text" class="form-control" value="{{ $order->user->name ?? 'Guest' }}" readonly>
                            <small class="text-muted">User ID: {{ $order->user_id }}</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Depan *</label>
                                    <input type="text" class="form-control" name="first_name" 
                                           value="{{ old('first_name', $order->first_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Belakang *</label>
                                    <input type="text" class="form-control" name="last_name" 
                                           value="{{ old('last_name', $order->last_name) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email', $order->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telepon *</label>
                            <input type="text" class="form-control" name="phone" 
                                   value="{{ old('phone', $order->phone) }}" required>
                        </div>
                    </div>

                    <!-- Shipping & Status Info -->
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-semibold border-bottom pb-2">Pengiriman & Status</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap *</label>
                            <textarea class="form-control" name="shipping_address" rows="3" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kota *</label>
                                    <input type="text" class="form-control" name="city" 
                                           value="{{ old('city', $order->city) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Pos *</label>
                                    <input type="text" class="form-control" name="postcode" 
                                           value="{{ old('postcode', $order->postcode) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Negara</label>
                            <input type="text" class="form-control" name="country" 
                                   value="{{ old('country', $order->country ?? 'Indonesia') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Pesanan *</label>
                                    <select class="form-select" name="status" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refund</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Pembayaran *</label>
                                    <select class="form-select" name="payment_status" required>
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Gagal</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran *</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="paypal" {{ $order->payment_method == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="check_payment" {{ $order->payment_method == 'check_payment' ? 'selected' : '' }}>Check Payment</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Items Display (Read-only) -->
                <div class="mt-4">
                    <h5 class="mb-3 fw-semibold border-bottom pb-2">Item Pesanan</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->product_image)
                                                <img src="{{ asset($item->product->product_image) }}" 
                                                     alt="{{ $item->product->product_name }}"
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div>{{ $item->product->product_name ?? 'Product Deleted' }}</div>
                                                <small class="text-muted">ID: {{ $item->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->item_total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold">Ongkir:</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle me-2"></i>
                            <div>
                                <small class="mb-0">
                                    Item pesanan tidak dapat diubah setelah dibuat. Untuk mengubah item pesanan, 
                                    silakan batalkan pesanan ini dan buat pesanan baru.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="mt-4 border-top pt-4">
                    <h5 class="mb-3 fw-semibold border-bottom pb-2">Informasi Tambahan</h5>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Order</label>
                                <input type="text" class="form-control" 
                                       value="{{ $order->created_at->format('d M Y, H:i') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">ID Order</label>
                                <input type="text" class="form-control" value="{{ $order->order_id }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Item</label>
                                <input type="text" class="form-control" 
                                       value="{{ $order->items->count() }} item" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.orders.show', $order->order_id) }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check"></i> Update Pesanan
                    </button>
                    
                    <!-- Quick Action Buttons -->
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ti ti-bolt"></i> Aksi Cepat
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="dropdown-item text-success">
                                        <i class="ti ti-check"></i> Tandai Selesai
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ti ti-x"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.orders.updatePaymentStatus', $order->order_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" class="dropdown-item text-success">
                                        <i class="ti ti-coin"></i> Tandai Lunas
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.form-control:focus, .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 500;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #e0a800;
    color: #000;
}

.alert-info {
    background-color: #e7f1ff;
    border-color: #cfe2ff;
    color: #084298;
}

.table-sm th, .table-sm td {
    padding: 0.75rem;
}

.table tfoot tr:last-child td {
    border-top: 2px solid #dee2e6;
    padding-top: 1rem;
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi sebelum aksi cepat
    document.querySelectorAll('.dropdown-item[type="submit"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            const action = this.textContent.trim();
            
            if (!confirm(`Yakin ingin ${action}?`)) {
                e.preventDefault();
            }
        });
    });
    
    // Konfirmasi sebelum submit form utama
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!confirm('Yakin ingin menyimpan perubahan?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
@endsection