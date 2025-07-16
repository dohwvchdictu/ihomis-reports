<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserAcc;
use Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginPage extends Component
{
    public $email = '';
    public $password = '';
    public $showPassword = false;
    public $errorMessage = '';
    public $identifier;

    public function login()
    {
        $this->validate([
            'identifier' => 'required',
            'password' => 'required',
        ]);

        // Get user by email first
        $user = User::where('email', $this->identifier)->first();


        if (!$user || !Hash::check($this->password, $user->password)) {
            $this->errorMessage = 'Invalid credentials.';
            return;
        }

        if ($user->active != 1) {
            $this->errorMessage = 'Account is not Activated!';
            return;
        }

        // All good, log in
        Auth::login($user);
        session()->regenerate();
        return redirect()->intended('/');
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }


    public function render()
    {
        return view('livewire.login-page')
            ->layout('components.layouts.login');
    }
}
