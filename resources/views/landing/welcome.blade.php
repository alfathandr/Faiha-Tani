<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Faiha Tani
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmIC8YSW_7W4xOkfD7a8eZHS0hhMkEfow&callback=initMap" async defer></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
      <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="/">
        Faiha Tani
      </a>
        <ul class="navbar-nav d-lg-block d-none">
          <li class="nav-item">
            <a href="/login" class="btn btn-sm mb-0 me-1 bg-gradient-light">Masuk</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
  <main class="main-content  mt-0">
  <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" 
     style="background-image: url('../assets/img/header.jpg'); background-position: top;">

      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-9 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Faiha Tani: Solusi Digital untuk Manajemen Suplai Barang Pertanian</h1>
            <p class="text-lead text-white">Mengelola stok, transaksi, dan distribusi barang pertanian dengan lebih mudah dan efisien!</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5>Faiha Tani</h5>
              <p class="text-lead text-dark px-8 ">Faiha Tani adalah platform berbasis web yang dirancang untuk membantu pelaku usaha pertanian dalam mengelola suplai barang secara digital. Dengan fitur yang lengkap, aplikasi ini mempermudah pencatatan stok, pemantauan transaksi, serta manajemen distribusi barang agar bisnis tetap berjalan lancar dan efisien.</p>
              <h5 class="pt-4">Produk Produk</h5>
            </div>

            
            <div class="container overflow-hidden mt-2" style="max-width: 90%; position: relative;">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php
                            $chunks = $products->chunk(4); // Membagi produk ke dalam grup berisi 4 item per slide
                            $isActive = true;
                        @endphp

                        @foreach ($chunks as $chunk)
                            <div class="carousel-item {{ $isActive ? 'active' : '' }}">
                                <div class="row px-xl-5 px-sm-4 px-3">
                                    @foreach ($chunk as $product)
                                        <div class="col-3 px-1">
                                            <div class="card shadow-sm border-0">
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 border-radius-lg">
                                                <div class="card-body text-center">
                                                    <h6 class="text-dark font-weight-bold mb-1">{{ $product->name }}</h6>
                                                    <p class="text-muted mb-1">Stock: {{ $product->stock }}</p>
                                                    <p class="text-success font-weight-bold">Rp. {{ number_format($product->price, 0, ',', '.') }},-</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @php $isActive = false; @endphp
                        @endforeach
                    </div>

                    <!-- Tombol Navigasi -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>


            <div class="card-body">

            <h5 class="pt-8 text-center justify-content-center">Toko Kami</h5>

                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-md-8">
                        <div class="card">
                            <div class="card-body px-0 py-0">
                                <div class="row">
                                    <div id="map-default" class="map-canvas" style="height: 600px;"></div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="card card-profile">
                            <img src="../assets/img/sampul.jpg" alt="Image placeholder" class="card-img-top">
                            <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">

                            </div>
                            <div class="card-body pt-0">

                                <div class="text-center">
                                    <h5>
                                    Muhammad  Haedir
                                    </h5>
                                    <div class="h6 font-weight-300">
                                    <i class="ni location_pin mr-2"></i>Bua, Luwu, Sulawesi Selatan
                                    </div>
                                    <div class="h6 mt-4">
                                    <i class="ni business_briefcase-24 mr-2"></i>Hubungi Kami
                                    </div>
                                    <div>
                                    <i class="ni education_hat mr-2"></i>+62 852 9999 4443
                                    </div>
                                </div>
                            <div class="d-flex text-center justify-content-center mt-2">
                                <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Whatsapp</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function initMap() {
      // Koordinat dari lokasi Google Maps yang kamu berikan
    
      var lokasi = { lat: -3.0821318, lng: 120.2291859 };
      // Inisialisasi Peta
      var map = new google.maps.Map(document.getElementById("map-default"), {
        center: lokasi,
        zoom: 15, // Sesuaikan zoom level
      });

      // Tambahkan Marker
      var marker = new google.maps.Marker({
        position: lokasi,
        map: map,
        title: "Lokasi Tujuan"
      });
    }
  </script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
