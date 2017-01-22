<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Users\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * @var AuthManager authenticationManager
     */
    private $authenticationManager;

    public function __construct(AuthManager $authenticationManager)
    {
        $this->authenticationManager = $authenticationManager;
        parent::__construct();
    }

    public function login()
    {
        return Socialite::driver('github')->scopes(['user:email'])->redirect();
    }

    public function logout()
    {
        $this->authenticationManager->logout();

        return redirect()->route('home');
    }

    public function loginComplete()
    {
        try {
            $githubUserObject = Socialite::driver('github')->user();
            $user = User::registerOrRetrieve($githubUserObject);

            $this->authenticationManager->login($user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->route('estimates');
    }
}
