<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginPage extends Component
{
    public $email = '';
    public $password = '';
    public $showPassword = false;
    public $errorMessage = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get user by email first
        $user = \App\Models\User::where('email', $this->email)->first();

        if (!$user || !\Hash::check($this->password, $user->password)) {
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
        return redirect()->intended('/admin/dashboard');
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
