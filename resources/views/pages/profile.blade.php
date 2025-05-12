@extends('app')
@section('title', 'Profil')
@section('content')


    <div class="card shadow-lg mx-4">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="../assets/img/user.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                	{{ Auth::user()->name }}
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
                Admin
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">Edit Profil</p>
              </div>
            </div>
            <div class="card-body">
              <form method="POST" action="{{ route('profil.update', Auth::user()->id) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Nama pengguna</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ Auth::user()->name }}"  required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Alamat email</label>
                        <input class="form-control" type="email" name="email" id="email" value="{{ Auth::user()->email }}"  required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="form-control-label">Password saat ini</label>
                          <input class="form-control" type="password" name="current_password" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="form-control-label">Password baru</label>
                          <input class="form-control" type="password" name="password">
                      </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection