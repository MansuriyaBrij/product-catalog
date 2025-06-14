<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Livewire\Attributes\Layout;

#[Layout('layouts.admin.guest')] //resources\views\layouts\admin\guest.blade.php
class Login extends Component
{
     public $email = '';
    public $password = '';
    public $error = '';

    public function login()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_admin' => true])) {
            session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        $this->error = 'Invalid credentials or not an admin.';
    }
    
    public function render()
    {
        return view('livewire.admin.auth.login');
    }
}
