<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Http\Livewire; use App\Models\User; use App\Models\UserAcc; use Hash; use Illuminate\Support\Facades\Auth; use Livewire\Component; class LoginPage extends Component { public $email = ''; public $password = ''; public $showPassword = false; public $errorMessage = ''; public $identifier; public function login() { $this->validate(['identifier' => 'required', 'password' => 'required']); $user = User::where('email', $this->identifier)->first(); if (!(!$user || !Hash::check($this->password, $user->password))) { goto g7VqE; } $this->errorMessage = 'Invalid credentials.'; return; g7VqE: if (!($user->active != 1)) { goto Q55wc; } $this->errorMessage = 'Account is not Activated!'; return; Q55wc: Auth::login($user); session()->regenerate(); return redirect()->intended('/'); } public function togglePasswordVisibility() { $this->showPassword = !$this->showPassword; } public function render() { return view('livewire.login-page')->layout('components.layouts.login'); } }
