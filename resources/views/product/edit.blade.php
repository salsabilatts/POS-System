@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Produk</h1>
    <form action="{{ route('admin.products.update', $product->id_product) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="product_name">Nama Produk</label>
            <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required>
        </div>
                <div class="mb-3">
            <label for="satuan" class="form-label">Unit</label>
            <select name="satuan" id="satuan" class="form-select" required>
                <option value="">Pilih Satuan</option>
                <option value="pcs" {{ old('satuan', $product->satuan ?? '') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                <option value="dus" {{ old('satuan', $product->satuan ?? '') == 'dus' ? 'selected' : '' }}>Dus</option>
                <option value="pack" {{ old('satuan', $product->satuan ?? '') == 'pack' ? 'selected' : '' }}>Pack</option>
                <option value="kg" {{ old('satuan', $product->satuan ?? '') == 'kg' ? 'selected' : '' }}>Kg</option>
                <option value="gram" {{ old('satuan', $product->satuan ?? '') == 'gram' ? 'selected' : '' }}>gr</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="price">Harga</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>
        <div class="mb-3">
            <label for="stock">Stok</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
