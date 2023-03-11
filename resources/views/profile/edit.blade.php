@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar->path) : asset('images/default-avatar.jpg') }}"
                            alt="avatar"
                            class="rounded-circle img-fluid" style="width: 150px;">
                        <form action="{{ route('profile.update.avatar', ['user' => Auth::user()->id]) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <h5 class="my-3">{{ $user->name }}</h5>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="avatar" id="avatar"/>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 float-end">Upload</button>
                        </form>

                    </div>
                </div>

            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">{{ __('Nom') }}</p>
                                </div>
                                <div class="col-sm-9">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name', $user->name) }}" required autocomplete="name"
                                           autofocus>
                                    <input type="hidden" name="user" value="{{ $user->id }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">{{ __('Adresse email') }}</p>
                                </div>
                                <div class="col-sm-9">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email', $user->email) }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Biographie</p>
                                </div>
                                <div class="col-sm-9">
                                    <textarea id="description"
                                              class="form-control  @error('description') is-invalid @enderror"
                                              name="description">{{ old('description', optional($user->profile)->description) }}</textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                    @enderror
                                </div>

                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Enregistrer') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
