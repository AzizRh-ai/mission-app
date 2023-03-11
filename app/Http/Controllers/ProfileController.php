<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $medias = $user->upload;
        return view('profile.index', [
            'user' => $user,
            'medias' => $medias
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $videos = $user->upload;
        $medias = $user->upload;
        return view('profile.index', [
            'user' => $user,
            'medias' => $medias
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', [
            'user' => $user,
            'success' => 'Profil mise à jour avec succès.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->profile->description = $request->input('description');
        $user->profile->save();

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Votre profil a été mis à jour avec succès !');
    }
}
