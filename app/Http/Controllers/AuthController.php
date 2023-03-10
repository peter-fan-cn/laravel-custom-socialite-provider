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
        $user   = Socialite::buildProvider(CodelocksProvider::class, $config)->user();

        Auth::login(new User([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email
        ]));
        return redirect('/home');
    }
}
