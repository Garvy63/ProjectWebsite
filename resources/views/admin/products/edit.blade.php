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

.form-container {
    background: white;
    border-radius: 8px;
    padding: 32px;
    border: 1px solid #e8e8e8;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.required {
    color: #ff7043;
    font-weight: 400;
}

.form-control {
    width: 100%;
    padding: 11px 14px;
    font-size: 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #ff7043;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control:disabled,
.form-control[readonly] {
    background: #f8f9fa;
    color: #666;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 13px;
    margin-top: 4px;
}

.input-group {
    display: flex;
}

.input-group-text {
    padding: 11px 14px;
    background: #f8f9fa;
    border: 1px solid #d1d5db;
    border-right: none;
    border-radius: 6px 0 0 6px;
    font-size: 14px;
    font-weight: 500;
    color: #666;
}

.input-group .form-control {
    border-radius: 0 6px 6px 0;
}

.current-image-box {
    background: #fafafa;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.current-image-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 10px;
    display: block;
}

.current-image {
    max-width: 220px;
    border-radius: 6px;
    border: 1px solid #e8e8e8;
}

.upload-zone {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 40px 24px;
    text-align: center;
    background: #fafafa;
    cursor: pointer;
    transition: all 0.2s;
}

.upload-zone:hover {
    border-color: #ff7043;
    background: #fff5f3;
}

.upload-label {
    font-size: 15px;
    font-weight: 500;
    color: #333;
    margin-bottom: 6px;
}

.upload-hint {
    font-size: 13px;
    color: #888;
}

.form-hint {
    font-size: 13px;
    color: #888;
    margin-top: 6px;
}

#imagePreview {
    margin-top: 20px;
}

.preview-box {
    background: #fafafa;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
}

.preview-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 10px;
    display: block;
}

#preview {
    max-width: 100%;
    max-height: 320px;
    border-radius: 6px;
    border: 1px solid #e8e8e8;
}

.preview-actions {
    margin-top: 12px;
}

.btn {
    padding: 11px 24px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #ff7043;
    color: white;
}

.btn-primary:hover {
    background: #f4511e;
}

.btn-secondary {
    background: white;
    color: #666;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    border-color: #999;
    color: #333;
}

.btn-danger {
    background: #f8f9fa;
    color: #dc3545;
    border: 1px solid #e8e8e8;
}

.btn-danger:hover {
    background: #dc3545;
    color: white;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    padding-top: 24px;
    margin-top: 24px;
    border-top: 1px solid #e8e8e8;
}

@media (max-width: 768px) {
    .form-container {
        padding: 24px;
    }
}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <h1 class="page-title">Edit Produk</h1>
            <div class="breadcrumb">
                <a href="{{ route('admin') }}">Dashboard</a> / 
                <a href="{{ route('admin.products.index') }}">Produk</a> / 
                Edit
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="form-container">
            <form action="{{ route('admin.products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">ID Produk</label>
                    <input type="text" 
                           class="form-control" 
                           value="{{ $product->product_id }}" 
                           readonly>
                    <div class="form-hint">ID tidak dapat diubah</div>
                </div>

                <div class="form-group">
                    <label for="product_name" class="form-label">
                        Nama Produk <span class="required">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('product_name') is-invalid @enderror" 
                           id="product_name" 
                           name="product_name" 
                           value="{{ old('product_name', $product->product_name) }}"
                           placeholder="Contoh: Adidas Samba Classic"
                           maxlength="100"
                           required>
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Maksimal 100 karakter</div>
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Kategori</label>
                    <input type="text" 
                           class="form-control @error('category') is-invalid @enderror" 
                           id="category" 
                           name="category" 
                           value="{{ old('category', $product->category) }}"
                           placeholder="Contoh: Sepatu, Tas, Pakaian"
                           maxlength="50">
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Opsional</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Produk</label>
                    
                    @if($product->product_image)
                    <div class="current-image-box">
                        <span class="current-image-label">Foto saat ini:</span>
                        <img src="{{ asset($product->product_image) }}" 
                             alt="{{ $product->product_name }}" 
                             class="current-image">
                    </div>
                    @endif
                    
                    <div class="upload-zone" id="uploadZone" onclick="document.getElementById('product_image').click()">
                        <div class="upload-label">Klik untuk ganti foto</div>
                        <div class="upload-hint">JPG, PNG, atau GIF - Maksimal 2MB</div>
                    </div>
                    
                    <input type="file" 
                           id="product_image"
                           name="product_image" 
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           style="display: none;"
                           onchange="previewImage(event)">
                    
                    @error('product_image')
                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                    @enderror
                    
                    <div class="form-hint">Kosongkan jika tidak ingin mengubah foto</div>
                    
                    <div id="imagePreview" style="display: none;">
                        <div class="preview-box">
                            <span class="preview-label">Preview foto baru:</span>
                            <img id="preview" src="" alt="Preview">
                            <div class="preview-actions">
                                <button type="button" class="btn btn-danger" onclick="removeImage()">
                                    Hapus Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="unit_price" class="form-label">
                        Harga Satuan <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" 
                               class="form-control @error('unit_price') is-invalid @enderror" 
                               id="unit_price" 
                               name="unit_price" 
                               value="{{ old('unit_price', $product->unit_price) }}"
                               placeholder="1500000"
                               step="0.01"
                               min="0"
                               required>
                    </div>
                    @error('unit_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Masukkan harga dalam Rupiah</div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('product_image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}
</script>
@endsection