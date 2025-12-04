@extends('layouts.admin')

@section('content')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.page-header {
    background: #f8f9fa;
    padding: 24px 28px;
    border-radius: 8px;
    border-left: 4px solid #ff7043;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left {
    flex: 1;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 6px;
}

.breadcrumb {
    font-size: 14px;
    color: #666;
}

.breadcrumb a {
    color: #666;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #ff7043;
}

.btn-add {
    padding: 10px 20px;
    background: #ff7043;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
}

.btn-add:hover {
    background: #f4511e;
    color: white;
}

.alert {
    padding: 14px 18px;
    border-radius: 6px;
    margin-bottom: 24px;
    font-size: 14px;
}

.alert-success {
    background: #f0f9f4;
    color: #1e7e34;
    border: 1px solid #c3e6cb;
}

.product-card {
    background: white;
    border-radius: 8px;
    padding: 24px;
    border: 1px solid #e8e8e8;
}

.card-header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e8e8e8;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
}

.stats-badge {
    background: #f8f9fa;
    color: #666;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.table-wrapper {
    overflow-x: auto;
}

.table-products {
    width: 100%;
    border-collapse: collapse;
}

.table-products thead {
    background: #fafafa;
}

.table-products th {
    padding: 12px 14px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid #e8e8e8;
}

.table-products td {
    padding: 14px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    vertical-align: middle;
}

.table-products tbody tr:hover {
    background: #fafafa;
}

.product-image {
    width: 52px;
    height: 52px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e8e8e8;
}

.no-image {
    width: 52px;
    height: 52px;
    background: #f8f9fa;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 11px;
}

.badge-id {
    background: #e8f4f8;
    color: #0277bd;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.product-name {
    font-weight: 500;
    color: #1a1a1a;
}

.badge-category {
    background: #fff5f0;
    color: #ff7043;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.price {
    font-weight: 600;
    color: #1a1a1a;
}

.action-buttons {
    display: flex;
    gap: 6px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
}

.btn-edit {
    background: #fff3e0;
    color: #f57c00;
}

.btn-edit:hover {
    background: #f57c00;
    color: white;
}

.btn-delete {
    background: #ffebee;
    color: #c62828;
}

.btn-delete:hover {
    background: #c62828;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-text {
    font-size: 15px;
    color: #666;
    margin-bottom: 8px;
}

.empty-state-hint {
    font-size: 13px;
    color: #999;
}

.pagination {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .table-products {
        font-size: 13px;
    }
}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Kelola Produk</h1>
                <div class="breadcrumb">
                    <a href="{{ route('admin') }}">Dashboard</a> / Produk
                </div>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-add">
                Tambah Produk
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="product-card">
            <div class="card-header-section">
                <h2 class="card-title">Daftar Produk</h2>
                <span class="stats-badge">Total: {{ $products->total() }} produk</span>
            </div>
            
            <div class="table-wrapper">
                <table class="table-products">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 8%;">Foto</th>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 30%;">Nama Produk</th>
                            <th style="width: 14%;">Kategori</th>
                            <th style="width: 18%;">Harga</th>
                            <th style="width: 17%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $index }}</td>
                            <td>
                                @if($product->product_image)
                                    <img src="{{ asset($product->product_image) }}" 
                                         alt="{{ $product->product_name }}" 
                                         class="product-image">
                                @else
                                    <div class="no-image">No Image</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-id">#{{ $product->product_id }}</span>
                            </td>
                            <td>
                                <span class="product-name">{{ $product->product_name }}</span>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge-category">{{ $product->category }}</span>
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="price">Rp {{ number_format($product->unit_price, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.products.edit', $product->product_id) }}" 
                                       class="btn-sm btn-edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->product_id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <p class="empty-state-text">Belum ada produk</p>
                                    <p class="empty-state-hint">Klik tombol "Tambah Produk" untuk menambahkan produk baru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection