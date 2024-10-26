@extends('layouts.template')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4"
                        style="background: linear-gradient(90deg, #343B41 0%, #343B41 100%);">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative">
                                    @if ($user->profile_image)
                                        <img src="{{ asset('storage/photos/' . $user->profile_image) }}"
                                            class="img-fluid rounded-circle shadow-lg"
                                            style="width: 160px; height: 160px; object-fit: cover; border: 3px solid #f8f9fa; transition: transform 0.3s ease-in-out;"
                                            onmouseover="this.style.transform='scale(1.2)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    @else
                                        <img src="{{ asset('polinema-bw.png') }}" class="img-fluid rounded-circle shadow-lg"
                                            style="width: 160px; height: 160px; object-fit: cover; border: 3px solid #f8f9fa; transition: transform 0.3s ease-in-out;"
                                            onmouseover="this.style.transform='scale(1.2)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    @endif
                                </div>
                                <h5 class="mt-3">{{ $user->nama }}</h5>
                                <p class="text-muted">{{ $user->username }}</p>
                            </div>
                            <div class="col-md-8">
                                <form method="POST" action="{{ route('profil.update', $user->user_id) }}"
                                    enctype="multipart/form-data">
                                    @method('PATCH')
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="username" class="form-label">{{ __('Username') }}</label>
                                        <input id="username" type="text"
                                            class="form-control shadow-sm @error('username') is-invalid @enderror"
                                            name="username" value="{{ $user->username }}" required autocomplete="username"
                                            placeholder="Enter your username">
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama" class="form-label">{{ __('Nama') }}</label>
                                        <input id="nama" type="text"
                                            class="form-control shadow-sm @error('nama') is-invalid @enderror"
                                            name="nama" value="{{ old('nama', $user->nama) }}" required
                                            autocomplete="nama" placeholder="Enter your full name">
                                        @error('nama')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="old_password" class="form-label">{{ __('Password Lama') }}</label>
                                        <input id="old_password" type="password"
                                            class="form-control shadow-sm @error('old_password') is-invalid @enderror"
                                            name="old_password" autocomplete="old-password"
                                            placeholder="Enter your old password">
                                        @error('old_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label ">{{ __('Password Baru') }}</label>
                                        <input id="password" type="password"
                                            class="form-control shadow-sm @error('password') is-invalid @enderror"
                                            name="password" autocomplete="new-password" placeholder="Enter new password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password-confirm"
                                            class="form-label ">{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm" type="password" class="form-control shadow-sm"
                                            name="password_confirmation" autocomplete="new-password"
                                            placeholder="Confirm new password">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="profile_image" class="form-label">{{ __('Ganti Foto Profil') }}</label>
                                        <input id="profile_image" type="file" class="form-control shadow-sm"
                                            name="profile_image">
                                    </div>
                                    <div class="form-group mb-0 text-end">
                                        <button type="submit" class="btn btn-primary shadow-sm px-4"
                                            style="background-color: #007bff; border: none; transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;"
                                            onmouseover="this.style.backgroundColor='#26a933'; this.style.boxShadow='0px 4px 15px rgba(0, 123, 255, 0.5)';"
                                            onmouseout="this.style.backgroundColor='#007bff'; this.style.boxShadow='none';">
                                            <i class="fas fa-save"></i> {{ __('Update Profil') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
