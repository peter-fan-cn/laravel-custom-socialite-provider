<?php

namespace App\Http\Controllers;

use App\Libraries\Socialite\CodelocksProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        $config = config('services.codelocks');
        return Socialite::buildProvider(CodelocksProvider::class, $config)->redirect();
    }


    public function callback()
    {
        $config = config('services.codelocks');
        $tokenUser   = Socialite::buildProvider(CodelocksProvider::class, $config)->user();
        $user = User::where('email', $tokenUser->email)->first();
        if(!$user) {
            $user = new User([
                //'id'    => $user->id,
                'name'  => $tokenUser->name,
                'email' => $tokenUser->email
            ]);
            $user->save();
        }
        Auth::login($user);
        return redirect('/home');
    }
}
