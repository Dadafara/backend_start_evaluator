<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log; // Importez la classe Log pour utiliser les logs

class GoogleController extends Controller
{
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
            $is_user = GoogleAuth::where('email', $user->getEmail())->first();

            if (!$is_user) {
                $saveUser = GoogleAuth::updateOrCreate(
                    [
                        'google_id' => $user->getId()
                    ],
                    [
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'password' => Hash::make($user->getName() . '@' . $user->getId()),
                    ]
                );
            } else {
                $saveUser = GoogleAuth::where('email', $user->getEmail())->update([
                    'google_id' => $user->getId(),
                ]);
                $saveUser = GoogleAuth::where('email', $user->getEmail())->first();
            }

            Auth::loginUsingId($saveUser->id);
            return redirect()->route('home');
        } catch (\Throwable $th) {
            Log::error('Google Auth Error: ' . $th->getMessage()); 
        }
    }

    public Function loginWithFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
}
