@extends('layout.main')
@section('title','Tambah Buku')
    
@section('content')
<div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Perpustakaan</h4>
                  <p class="card-description">
                    Input Data Buku
                  </p>
                  <form method="POST" action="{{ route('buku.store') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <label for="kode_buku">Kode Buku</label>
                      <input type="text" class="form-control" id="kode_buku" placeholder="kode buku">
                    </div>
                    <div class="form-group">
                      <label for="judul">Judul</label>
                      <input type="text" class="form-control" id="judul" placeholder="judul">
                    </div>
                    <div class="form-group">
                      <label for="pengarang">Pengarang</label>
                      <input type="text" class="form-control" id="pengarang" placeholder="pengarang">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Penerbit</label>
                      <input type="text" class="form-control" id="exampleInputPassword1" placeholder="penerbit">
                    </div>
                    <div class="form-group">
                      <label for="tahun_terbit">Tahun Terbit</label>
                      <input type="date" class="form-control" id="tahun_terbit" placeholder="tahun_terbit">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
  
    
@endsection