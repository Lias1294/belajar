@extends('layout.main')
@section('title','Buku')
    
@section('content')
<div class="card-body">
                  <h4 class="card-title">BUKU</h4>
                  <a href="{{route('buku.create')}}" class="btn btn-info">Tambah Buku</a>
                  <p class="card-description">
                    LIST BUKU 
                  </p>
                  <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>
                            Kode Buku
                          </th>
                          <th>
                            Judul
                          </th>
                          <th>
                            Pengarang
                          </th>
                          <th>
                            Penerbit
                          </th>
                          <th>
                            Tahun Terbit
                          </th>
                           <th>
                            Aksi
                          </th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>

    
@endsection