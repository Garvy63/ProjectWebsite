@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <!-- Header -->
    <div class="d-flex align-items-start mb-4">
        <div class="border-start border-primary border-4 ps-3" style="border-width: 4px !important;">
            <h4 class="mb-1 fw-bold">Buat Pesanan Baru</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
                    <li class="breadcrumb-item active">Buat Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Create Order -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                @csrf

                <div class="row">
                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-semibold border-bottom pb-2">Informasi Pelanggan</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Pelanggan</label>
                            <select class="form-select" id="userSelect">
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            data-firstname="{{ $user->customer->first_name ?? $user->name }}"
                                            data-lastname="{{ $user->customer->last_name ?? '' }}"
                                            data-email="{{ $user->email }}"
                                            data-phone="{{ $user->customer->phone_number ?? '' }}"
                                            data-address="{{ $user->address->address_line_01 ?? '' }}"
                                            data-city="{{ $user->address->town_city ?? '' }}"
                                            data-postcode="{{ $user->address->postcode_zip ?? '' }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="user_id" id="user_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Depan *</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Belakang *</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telepon *</label>
                            <input type="text" class="form-control" name="phone" id="phone" required>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-semibold border-bottom pb-2">Alamat Pengiriman</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap *</label>
                            <textarea class="form-control" name="shipping_address" id="shipping_address" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kota *</label>
                                    <input type="text" class="form-control" name="city" id="city" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Pos *</label>
                                    <input type="text" class="form-control" name="postcode" id="postcode" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Negara</label>
                            <input type="text" class="form-control" name="country" value="Indonesia">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran *</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="cash">Tunai</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="mt-4">
                    <h5 class="mb-3 fw-semibold border-bottom pb-2">Produk Pesanan</h5>
                    
                    <div id="productsContainer">
                        <div class="product-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="form-label">Produk *</label>
                                    <select class="form-select product-select" name="products[0][product_id]" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->product_id }}" 
                                                    data-price="{{ $product->unit_price }}"
                                                    data-stock="{{ $product->stock }}">
                                                {{ $product->product_name }} (Rp {{ number_format($product->unit_price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Jumlah *</label>
                                    <input type="number" class="form-control quantity-input" 
                                           name="products[0][quantity]" min="1" value="1" required>
                                    <small class="text-muted stock-info">Stok: 0</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Harga Satuan</label>
                                    <input type="text" class="form-control price-input" readonly value="Rp 0">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-product" style="margin-top: 2rem;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm" id="addProduct">
                        <i class="ti ti-plus"></i> Tambah Produk
                    </button>
                </div>

                <!-- Summary -->
                <div class="mt-4 border-top pt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-semibold">Ringkasan Pesanan</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-semibold" id="subtotalDisplay">Rp 0</span>
                                <input type="hidden" name="subtotal" id="subtotal" value="0">
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ongkir:</span>
                                <span class="fw-semibold">Rp 50.000</span>
                                <input type="hidden" name="shipping_cost" value="50000">
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold text-primary" id="totalDisplay">Rp 50.000</span>
                                <input type="hidden" name="total" id="total" value="50000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check"></i> Buat Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
let productIndex = 1;

// Auto-fill customer data
document.getElementById('userSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        document.getElementById('first_name').value = selectedOption.dataset.firstname || '';
        document.getElementById('last_name').value = selectedOption.dataset.lastname || '';
        document.getElementById('email').value = selectedOption.dataset.email || '';
        document.getElementById('phone').value = selectedOption.dataset.phone || '';
        document.getElementById('shipping_address').value = selectedOption.dataset.address || '';
        document.getElementById('city').value = selectedOption.dataset.city || '';
        document.getElementById('postcode').value = selectedOption.dataset.postcode || '';
        
        // Set user_id hidden field
        document.getElementById('user_id').value = selectedOption.value;
    }
});

// Add product row
document.getElementById('addProduct').addEventListener('click', function() {
    const template = `
        <div class="product-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">Produk *</label>
                    <select class="form-select product-select" name="products[${productIndex}][product_id]" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->product_id }}" 
                                    data-price="{{ $product->unit_price }}"
                                    data-stock="{{ $product->stock }}">
                                {{ $product->product_name }} (Rp {{ number_format($product->unit_price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah *</label>
                    <input type="number" class="form-control quantity-input" 
                           name="products[${productIndex}][quantity]" min="1" value="1" required>
                    <small class="text-muted stock-info">Stok: 0</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga Satuan</label>
                    <input type="text" class="form-control price-input" readonly value="Rp 0">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm remove-product" style="margin-top: 2rem;">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('productsContainer').insertAdjacentHTML('beforeend', template);
    productIndex++;
    
    // Attach events to new elements
    attachProductEvents();
});

// Remove product row
function attachProductEvents() {
    document.querySelectorAll('.remove-product').forEach(button => {
        button.addEventListener('click', function() {
            if (document.querySelectorAll('.product-item').length > 1) {
                this.closest('.product-item').remove();
                calculateTotal();
            }
        });
    });
    
    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.dataset.price || 0;
            const stock = selectedOption.dataset.stock || 0;
            
            // Update price input
            const priceInput = this.closest('.row').querySelector('.price-input');
            priceInput.value = 'Rp ' + Number(price).toLocaleString('id-ID');
            
            // Update stock info
            const stockInfo = this.closest('.row').querySelector('.stock-info');
            stockInfo.textContent = 'Stok: ' + stock;
            
            // Update quantity max
            const quantityInput = this.closest('.row').querySelector('.quantity-input');
            quantityInput.max = stock;
            
            calculateTotal();
        });
    });
    
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
}

// Calculate total
function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('.product-item').forEach(item => {
        const select = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        
        if (select.value && quantityInput.value) {
            const price = select.options[select.selectedIndex].dataset.price || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            subtotal += price * quantity;
        }
    });
    
    const shipping = 50000;
    const total = subtotal + shipping;
    
    // Update displays
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    // Update hidden inputs
    document.getElementById('subtotal').value = subtotal;
    document.getElementById('total').value = total;
}

// Initialize events
attachProductEvents();

// Form validation
document.getElementById('orderForm').addEventListener('submit', function(e) {
    const productItems = document.querySelectorAll('.product-item');
    let hasProduct = false;
    
    productItems.forEach(item => {
        const select = item.querySelector('.product-select');
        if (select.value) hasProduct = true;
    });
    
    if (!hasProduct) {
        e.preventDefault();
        alert('Minimal pilih satu produk!');
        return false;
    }
});
</script>
@endsection

<style>
.product-item {
    background-color: #f8f9fa;
}

.product-item:hover {
    background-color: #f1f3f5;
}

.remove-product {
    transition: all 0.2s ease;
}

.remove-product:hover {
    transform: scale(1.1);
}
</style>
@endsection