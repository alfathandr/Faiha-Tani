@extends('app')
@section('title', 'Beranda')
@section('content')


        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-12">
            <div class="card overflow-hidden mb-2">
                
                <div style="background-image: url('{{ asset('assets/img/header_product.jpg') }}');
                            background-size: contain; 
                            background-position: center;
                            background-repeat: no-repeat; 
                            height: 350px;
                            width: 100%;">
                </div>
            </div>
        </div>

      <livewire:report-header /> 
      <livewire:report-mk-chart /> 
      


@endsection
