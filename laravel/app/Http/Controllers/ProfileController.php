<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\DeliveryAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile', [
            'user'      => $request->user(),
            'addresses' => $request->user()->addresses()->orderByDesc('is_default')->get(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'street'          => ['required', 'string', 'max:100'],
            'building_number' => ['nullable', 'string', 'max:20'],
            'city'            => ['required', 'string', 'max:100'],
            'postal_code'     => ['required', 'string', 'max:20'],
            'country'         => ['required', 'string', 'max:100'],
            'is_default'      => ['boolean'],
        ]);

        if (!empty($data['is_default'])) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        $request->user()->addresses()->create(array_merge(
            $data,
            ['created_at' => now(), 'is_default' => !empty($data['is_default'])]
        ));

        return Redirect::route('profile.edit')->with('status', 'address-added');
    }

    public function destroyAddress(Request $request, DeliveryAddress $address): RedirectResponse
    {
        abort_if($address->user_id !== $request->user()->id, 403);

        $address->delete();

        return Redirect::route('profile.edit')->with('status', 'address-deleted');
    }

    public function avatarUpdate(Request $request): RedirectResponse
    {
        $request->validate(['avatar' => ['required', 'image', 'max:2048']]);

        $user = $request->user();
        $ext  = $request->file('avatar')->getClientOriginalExtension();
        $path = $request->file('avatar')->storeAs('avatars', $user->id . '.' . $ext, 'public');
        $user->avatar_url = '/storage/' . $path;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    public function avatarDelete(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->avatar_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar_url));
            $user->avatar_url = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-deleted');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
