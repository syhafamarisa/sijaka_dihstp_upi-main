<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

if (!$user) {

    $user = User::create([
        'name' => $googleUser->getName(),
        'email' => $googleUser->getEmail(),
        'google_id' => $googleUser->getId(),
        'avatar' => $googleUser->getAvatar(),
        'password' => bcrypt(Str::random(16)),
        'role' => 'user', // sesuaikan dengan nama kolom role
    ]);

} else {

    $user->update([
        'google_id' => $googleUser->getId(),
        'avatar' => $googleUser->getAvatar(),
    ]);
}

Auth::login($user);

return redirect()->route('dashboard');
    }
}