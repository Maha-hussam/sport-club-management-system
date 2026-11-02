<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PlayerProfileImageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        $player = $request->user();

        if ($player->profile_image) {
            Storage::disk('public')->delete($player->profile_image);
        }

        $path = $request->file('profile_image')
            ->store('profile-images', 'public');

        $player->update([
            'profile_image' => $path,
        ]);

        return back()->with(
            'success',
            'Profile image uploaded successfully.'
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        $player = $request->user();

        if ($player->profile_image) {
            Storage::disk('public')->delete($player->profile_image);

            $player->update([
                'profile_image' => null,
            ]);
        }

        return back()->with(
            'success',
            'Profile image removed successfully.'
        );
    }
}