@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    $('.alert-success').fadeOut('slow');
                }, 4000);
            </script>
        @endif
        <div class="alert alert-info d-none alert-progress progressMedia" role="alert"></div>
        <div class="alert alert-info avatarImg d-none"></div>
        <div class="alert alert-success deleteMedia d-none"></div>
        <div class="alert alert-danger error d-none"></div>
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
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="profile-image-container">
                            <img
                                src="{{ $user->avatar ? asset('storage/'.$user->avatar->path) : asset('img/default.png') }}"
                                class="profile-image" id="avatarImg" alt="Profile image">
                            @if(Auth::check() && Auth::user()->id == $user->id)
                                <label for="avatar" class="edit-icon-label">
                                    <div class="edit-icon-container">
                                        <i class="fas fa-pencil-alt"></i>
                                    </div>
                                </label>
                                <form action="{{ route('profile.update.avatar', ['user' => Auth::user()->id]) }}"
                                      method="POST" enctype="multipart/form-data" id="avatar-form" data-upload-type="avatar">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="type" id="avatar-file" value="avatar">
                                    <input type="file" name="file" id="avatar" class="edit-icon-input" accept="image/*"
                                           onchange="uploadMedia('#avatar-form')">
                                </form>
                            @endif

                        </div>
                        <hr>
                        {{ $user->name}}
                    </div>

                </div>
            </div>
            <div class="col-lg-8">
                @if(Auth::check() && Auth::user()->id == $user->id)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Nom</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->name }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Biographie</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->profile->description ?? 'Aucun' }}</p>
                            </div>

                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-danger mt-3 float-end">Modifier mon
                            profil</a>
                    </div>
                </div>
            </div>
            @else
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Information</div>

                                <div class="card-body">
                                    Information Confidentiel
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <hr>

        </div>
    @if(Auth::check() && Auth::user()->id == $user->id)
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <li class="nav-item">
                                <a class="nav-link active" id="v-pills-logo-tab" data-bs-toggle="pill" href="#v-pills-logo" role="tab" aria-controls="v-pills-logo" aria-selected="true">Logo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="v-pills-video-tab" data-bs-toggle="pill" href="#v-pills-video" role="tab" aria-controls="v-pills-video" aria-selected="false">Video</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="v-pills-quizz-tab" data-bs-toggle="pill" href="#v-pills-quizz" role="tab" aria-controls="v-pills-quizz" aria-selected="false">Quizz</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-logo" role="tabpanel" aria-labelledby="v-pills-logo-tab">
                                    <div class="row" id="logo-panel">
                                        @if ($medias->where('filetype', 'logos')->isEmpty())
                                            <div class="d-flex justify-content-center align-items-center h-100" id="logo-empty">
                                                <div class="text-center">
                                                    <i class="fas fa-photo-video fa-5x mb-3"></i>
                                                    <p class="lead mb-2">Aucun média trouvé</p>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                                        Upload Logo
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                        @foreach($medias as $media)
                                            @if($media->filetype == 'logos')
                                                <div class="col-md-4 mb-4" id="video-{{ $media->id }}">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <img src="{{ asset('storage/'.$media->path) }}" class="logo-image" id="logoImg" alt="Logo image" style="max-width:100%; max-height:100%;">
                                                        </div>
                                                        <div class="card-footer text-muted">
                                                            <span class="float-start"><h5 class="card-title">{{ $media->title }}</h5></span>
                                                            <form action="{{ route('delete.video', ['id' => $media->id]) }}" method="POST"  class="delete-form float-end">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger delete-btn" data-id="{{ $media->id }}">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif
                                        @endforeach

                                        @endif
                                    </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-video" role="tabpanel" aria-labelledby="v-pills-video-tab">
                                <div class="row" id="video-panel">
                                    @if ($medias->where('filetype', 'videos')->isEmpty())
                                        <div class="d-flex justify-content-center align-items-center h-100" id="video-empty">
                                            <div class="text-center">
                                                <i class="fas fa-photo-video fa-5x mb-3"></i>
                                                <p class="lead mb-2">Aucun média trouvé</p>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                                    Upload Video
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                    @foreach($medias as $media)
                                        @if($media->filetype == 'videos')
                                            <div class="col-md-4 mb-4" id="video-{{ $media->id }}">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <video controls width="100%" height="100%">
                                                            <source src="{{ asset('storage/'.$media->path) }}" type="video/mp4">
                                                        </video>
                                                    </div>
                                                    <div class="card-footer text-muted">
                                                        <span class="float-start"><h5 class="card-title">{{ $media->title }}</h5></span>
                                                        <form action="{{ route('delete.video', ['id' => $media->id]) }}" method="POST"  class="delete-form float-end">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger delete-btn" data-id="{{ $media->id }}">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-quizz" role="tabpanel" aria-labelledby="v-pills-quizz-tab">
                                <h3>Contenu Quizz</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="{{asset('js/upload.js')}}"></script>
@endsection
