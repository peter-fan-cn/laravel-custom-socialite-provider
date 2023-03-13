<?php

namespace App\Http\Controllers;

use App\Libraries\Socialite\CodelocksProvider;
use App\Models\OAuth\Provider;
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
        $config    = config('services.codelocks');
        $tokenUser = Socialite::buildProvider(CodelocksProvider::class, $config)->user();
        $provider  = Provider::with('user')
            ->where(['sub' => $tokenUser->id, 'provider' => 'Codelocks'])
            ->first();
        if (!$provider) {
            $provider = new Provider([
                'sub' => $tokenUser->id,
                'provider' => 'Codelocks',
                'name'   => $tokenUser->name,
                'email'  => $tokenUser->email,
                'avatar' => $tokenUser->avatar,
            ]);
        }
        $user = $provider->findOrCreateUser();
        Auth::login($user);
        return redirect('/home');
    }
}
