<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Edit Profile';
        $data['user'] = auth()->user();
        return view('user.profile', $data);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(auth()->id());
        $avatar = $user->avatar;

        $request->validate([
            'name'        => 'required|string|max:50',
            'phone'       => 'nullable|numeric',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'address'     => 'nullable|string|max:500',
            'designation' => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = 'assets/uploads/users_avatar/';
            $path = $file->move($directory, $filename);
            $avatar = $path;
        }

        $user->update([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'email'       => $request->email,
            'address'     => $request->address,
            'designation' => $request->designation,
            'avatar'      => $avatar
        ]);

        return back()->withSuccess('Profile updated successfully.');
    }

    public function username_update(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $request->validate([
            'old_username' => 'required|string',
            'new_username' => 'required|string|min:3|max:30|confirmed|unique:users,username,' . $user->id,
        ]);

        if ($request->old_username !== $user->username) {
            return back()->withInput()->withError('Old username is incorrect.');
        }

        if ($request->old_username == $request->new_username) {
            return back()->withInput()->withError('New username must be different from old username.');
        }

        $user->update([
            'username' => $request->new_username
        ]);

        return back()->withSuccess('Username updated successfully.');
    }

    public function password_update(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:4|confirmed|different:old_password',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withInput()->withError('The old password does not match our records.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->withSuccess('Password updated successfully!');
    }
}
