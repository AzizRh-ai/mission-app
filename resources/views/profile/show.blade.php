@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Profile') }}</div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $user->name }}</h5>

                        <p class="card-text">{{ $user->email }}</p>

                        <p class="card-text">{{ optional($user->profile)->description or 'Aucune description disponible' }}</p>

                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar->path) }}" class="img-fluid"
                                 alt="{{ $user->name }} Avatar">
                        @endif
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">{{ __('Edit Profile') }}</a>
            </div>
        </div>
    </div>
@endsection
