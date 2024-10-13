@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Kategori</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('kategori') }}">
            @csrf
            <div class="form-group">
                <label for="kategori_kode">Kode Kategori</label>
                <input type="text" class="form-control" id="kategori_kode" name="kategori_kode" value="{{ old('kategori_kode') }}" required>
                @error('kategori_kode')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="kategori_nama">Nama Kategori</label>
                <input type="text" class="form-control" id="kategori_nama" name="kategori_nama" value="{{ old('kategori_nama') }}" required>
                @error('kategori_nama')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ url('kategori') }}" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>
@endsection
