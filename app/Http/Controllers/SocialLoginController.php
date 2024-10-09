<?php

namespace App\Http\Controllers;

use App\Models\SocialLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
class SocialLoginController extends Controller
{
    public function toProvider($driver){
        return Socialite::driver($driver)->redirect();
    }

    public function handleCallBack($driver){
        $user = Socialite::driver($driver)->stateless()->user();
        $userSocialAccount = SocialLogin::where('provider', $driver)->where('provider_id', $user->getId())->first();
        if($userSocialAccount){
            $auth = Auth::login($userSocialAccount->user);
            Session::regenerate();
            $authUser = Auth::user();
            return redirect()->intended('dashboard');
        }


        $registeredUser = User::where('email', $user->getEmail())->first();
        if($registeredUser){
            SocialLogin::create([
                'user_id' => $registeredUser->id,
                'provider' => $driver,
                'provider_id' => $user->getId()
            ]);
        }else{
            $registeredUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(rand(100, 1000))
            ]);

            SocialLogin::create([
                'user_id' => $registeredUser->id,
                'provider' => $driver,
                'provider_id' => $user->getId()
            ]);
        }
        Auth::login($registeredUser);
        Session::regenerate();
        dd(Auth::user());
        return redirect()->route('dashboard');
    }
    
}
