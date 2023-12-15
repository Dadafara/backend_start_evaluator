<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSimple; 
use Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    
    public function registration()
    {
        return view('auth.registration');
    }

    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']); // Hasher le mot de passe
        $createUser = $this->create($data);

        return redirect('login')->withSuccess('Vous êtes enregistré.');
    }

    public function create(array $data)
    {
        return UserSimple::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $checkLoginCredentials = $request->only('email', 'password');
        if (Auth::attempt($checkLoginCredentials)) {
            return redirect('home')->withSuccess('Vous êtes connecté.');
        }
        return redirect('login')->withErrors(['login' => 'Adresse e-mail ou mot de passe incorrect.']);
    }


    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}