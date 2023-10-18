<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.show')->with('user', $user);
    }

    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.profile.edit')->with('user', $user);
    }

    public function update(Request $request)//(Auth::user()->id)がすでにeditにあるから$idは不要
    {
        # Validate the data from the form
        $request->validate([
            'name'           => 'required|min:1|max:50',
            'email'          => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar'          => 'nullable|mimes:jpg,png,jpeg,gif|max:1048',
            'Introduction'    => 'max:100',
        ]);

        # Update the profile
        $user                 = $this->user->findOrFail(Auth::user()->id);
        $user->name           = $request->name;
        $user->email          = $request->email;
        $user->avatar         = $request->avatar;
        $user->introduction   = $request->introduction;

        //If yhe user uploaded an avatar, update it.
        if($request->avatar){
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }
        //$request->avatar->extension()はformで書き換えようとしているファイルの状態(jpg,pngなど)に合わせてその文字を挿入してくれる？
        //上のbase64の段階で写真データを文字(string)に書き換えている(converted)から、写真をdeleteする必要はない。

        # Save the new profile table
        $user->save();

        # Redirect to show user page (to confirm the update)
        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }
}
