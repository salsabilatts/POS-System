@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Produk</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_product">ID Produk</label>
            <input type="text" name="id_product" class="form-control" value="{{ old('id_product') }}" required>
        </div>
        <div class="mb-3">
            <label for="product_name">Nama Produk</label>
            <input type="text" name="product_name" class="form-control" required>
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
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="stock">Stok</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
