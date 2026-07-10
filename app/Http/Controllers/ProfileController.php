<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'contact_no' => ['required', 'string', 'max:20'],
            'image' => ['nullable'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->contact_no = $validated['contact_no'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (!empty($request->input('image'))) {
            $imageFile = new File(Storage::path($request->input('image')));
            $user->addMedia($imageFile)->toMediaCollection('picture');
        }

        return redirect()->route('profile.edit')
            ->with('success', __('general.profile_updated'));
    }
}
