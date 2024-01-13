<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePassword extends Component
{
    public $currentPassword;
    public $password;
    public $password_confirmation;
    public $successMessage;

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['currentPassword', 'password', 'password_confirmation']);
        $this->successMessage = 'Password updated successfully!';
    }

    public function render()
    {
        return view('livewire.auth.update-password');
    }
}
