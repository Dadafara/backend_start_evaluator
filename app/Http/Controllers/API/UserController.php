<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\JsonResponse;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Facades\Socialite;
// use Auth;
// use Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),
            [
                'website' => 'required|url',
                'compagnie_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'country' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validation erreur',
                    'errors'=>$validator->errors()
                ], 401);
            }
            $user = user::create([
                'website' => $request->website,
                'compagnie_name' => $request->compagnie_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'job_title' => $request->job_title,
                'email' => $request->email,
                'country' => $request->country,
                'contact' => $request->contact,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Création du compte avec succès',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }

    }



    public function loginUser(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur validation',
                    'errors' => $validator->errors(),
                ],401);
            }
            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de mot de passe ou adresse email.',
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'Connexion reussi.',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ],200);
        } catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }

    }


    public function userDetails(): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response(['data' => $user],200);
        }
        return response(['data' => 'Unauthorized'],401);
    }


    public function logout(): Response
    {
        $user =Auth::user();
        $user->currentAccessToken()->delete();
        return Response(['data' => 'Déconnexion réussie.'],200);
    }


    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );
        $token = $userCreated->createToken('token-name')->plainTextToken;

        return response()->json($userCreated, 200, ['Access-Token' => $token]);
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return response()->json(['error' => 'Please login using facebook, github or google'], 422);
        }
    }
}
