@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-info progressMedia d-none"></div>
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Accueil</div>

                    <div class="card-body">
                        Vous êtes maintenant connecté!
                        <div class="text-center mt-2">
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary" >
                                Allez au profil
                            </a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/upload.js') }}"></script>
@endsection
