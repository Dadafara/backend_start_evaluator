<?php

namespace App\Http\Controllers\api\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        $this->middleware(['social']);
    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callback($service){
        try{
            $serviceUser = Socialite::driver($service)->stateless()->user();
        }catch(InvalidStateException $e){
            return redirect(env('CLIENT_BASE_URL') . '?error=Unable to login using' . $service .'.Please try again');
        }
        $email = $serviceUser->getEmail();
        if($service != 'google'){
            $email = $serviceUser->getId() . '@' . $service . '.local';
        }

        $user = $this->getExistingUser($serviceUser, $email, $service);
        // $newUser = false;
        if (!$user) {
            // $newUser = true;
            $user = User::create([
                'name' => $serviceUser->getName(),
                'email' => $email,
                'password' => ''
            ]);
        }
        if ($this->needsToCreateSocial($user, $service)) {
            UserSocial::create([
                'user_id' => $user->id,
                'social_id' => $serviceUser->getId(),
                'service' => $service
            ]);
        }
        return redirect (env('CLIENT_BASE_URL'). '?token=' . $this->auth->fromUser($user));
    }

    public function needsToCreateSocial(User $user, $service)
    {
        return !$user->hasSocialLinkend($service);
    }

    public function getExistingUser($serviceUser, $email, $service)
    {
        if ($service == 'google') {
            return User::where('email', $email)->orWhereHas('social', function($q) use ($serviceUser, $service) {
                $q->Where('social_id', $serviceUser->getId())->where('service', $service);
            })->first();
        } else {
            $userSocial = UserSocial ? $userSocial->user : null;
        }
    }

    
}
