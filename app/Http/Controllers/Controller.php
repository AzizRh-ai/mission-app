<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function uploadMedia(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:50000',
        ]);

        $user = auth()->user();
        if ($request->hasFile('file')) {
            $type = $request->input('type');

            $path = $request->file('file')->store($type, 'public');

            if ($type === 'avatar') {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar->path);

                    $user->avatar->path = $path;
                    $user->avatar->save();
                } else {
                    $user->avatar()->create([
                        'path' => $path,
                    ]);
                }
            } else if ($type === 'videos') {
                $title = $request->input('title');


                $video = $user->upload()->create([
                    'title' => $title,
                    'path' => $path,
                    'filetype' => $type
                ]);

                $uploadId = $video->id;
                return response()->json([
                        'success' => 'Video uploadé avec succès.',
                        'url' => asset('storage/' . $path),
                        'title' => $title,
                        'type' => $type,
                        'id' => $uploadId
                    ]
                );
            } else if ($type === 'logos') {
                // Upload de logos
                $title = $request->input('title');
                $path = $request->file('file')->store('logos', 'public');

                $logo = $user->upload()->create([
                    'title' => $title,
                    'path' => $path,
                    'filetype' => $type
                ]);

                $uploadId = $logo->id;

                return response()->json([
                        'success' => 'Image uploadé avec succès.',
                        'url' => asset('storage/' . $path),
                        'type' => $type,
                        'title' => $title,
                        'id' => $uploadId
                    ]
                );
            }

            return response()->json([
                    'success' => 'Image uploadé avec succès.',
                    'url' => asset('storage/' . $path),
                    'type' => $type
                ]
            );
        }

        return response()->json(['error' => 'File not found.']);
    }

    public function deleteMedia($id)
    {
        $upload = Upload::findOrFail($id);

        if (auth()->user()->id !== $upload->user_id) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($upload->path);

        $upload->delete();

        return response()->json([
                'success' => 'Fichier supprimé avec succès.',
            ]
        );
    }
}
