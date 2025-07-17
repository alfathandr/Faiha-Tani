@extends('app')
@section('title', 'Beranda')
@section('content')


        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-12">
            <div class="card overflow-hidden mb-2">
<<<<<<< HEAD

                <div style="background-image: url('{{ asset('assets/img/header_product.jpg') }}');
                            background-size: cover;
                            background-position: center;
                            height: 300px;">
=======
                
                <div style="background-image: url('{{ asset('assets/img/header_product.png') }}');
                            background-size: contain;      /* Diubah dari cover menjadi contain */
                            background-position: center;
                            background-repeat: no-repeat;  /* Ditambahkan agar gambar tidak berulang */
                            height: 250px;
                            width: 100%;">   {{-- Opsional: beri warna latar belakang --}}
>>>>>>> c489dea7cf06c82efa8077ba2f4c204bf62093ac
                </div>
            </div>
        </div>

<<<<<<< HEAD
      <livewire:report-header />
      <livewire:report-mk-chart />

=======
      <livewire:report-header /> 
      <livewire:report-mk-chart /> 
      
>>>>>>> c489dea7cf06c82efa8077ba2f4c204bf62093ac


@endsection


