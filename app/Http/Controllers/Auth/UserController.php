<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @return Factory|View
     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * @param LoginUserRequest $request
     * @return RedirectResponse
     */
    public function signIn(LoginUserRequest $request)
    {
        $params = $request->only([
            'email',
            'password',
            'remember',
        ]);

        if (Auth::attempt([
            'email'    => $params['email'],
            'password' => $params['password']
        ], isset($params['remember']))) {
            return redirect()->intended(route('main'));
        }

        return redirect()->route('login')->withErrors(['password' => 'Invalid credentials.']);
    }

    /**
     * @return Factory|View
     */
    public function register()
    {
        return view('user.register');
    }

    /**
     * @param RegisterUserRequest $request
     * @return RedirectResponse
     */
    public function signUp(RegisterUserRequest $request)
    {
        $user = User::query()->create($request->only([
            'name',
            'email',
            'password',
        ]));

        event(new Registered($user));

        return redirect()->route('login');
    }
}
