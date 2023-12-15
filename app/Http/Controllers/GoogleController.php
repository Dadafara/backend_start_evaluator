<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public Function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public Function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
            $is_user = GoogleAuth::where('email',$user->getEmail())->first();
            if (!$is_user) {
                $saveUser = GoogleAuth::updateOrCreate(
                    [
                        'google_id' => $user->getId()
                    ],
                    [
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'password' => Hash::make($user->getName().'@'.$user->getId()),
                        // 'password' => Hash::make($request->password),
                    ]
                    );
            }else{
                $saveUser = GoogleAuth::where('email', $user->getEmail())->update([
                    'google_id' => $user->getId(),
                ]);
                $saveUser = GoogleAuth::where('email',$user->getEmail())->first();
            }
            Auth::loginUsingId($saveUser->id);
            return redirect()->route('home');


        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public Function loginWithFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
}
