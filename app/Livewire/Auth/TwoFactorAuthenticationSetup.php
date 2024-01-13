<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TwoFactorAuthenticationSetup extends Component
{
    public $confirmingEnable = false;
    public $confirmingDisable = false;

    public function confirmEnable()
    {
        $this->confirmingEnable = true;
    }

    public function enableTwoFactorAuthentication()
    {
        Auth::user()->forceFill([
            'two_factor_secret' => encrypt(Auth::user()->twoFactorAuth()->recoveryCode()),
            'two_factor_recovery_codes' => encrypt(json_encode(Auth::user()->twoFactorAuth()->recoveryCodes())),
        ])->save();

        $this->confirmingEnable = false;

        $this->dispatch('refresh-navigation-dropdown');
        $this->dispatch('notify', 'Two-Factor Authentication enabled successfully!');
    }

    public function confirmDisable()
    {
        $this->confirmingDisable = true;
    }

    public function disableTwoFactorAuthentication()
    {
        Auth::user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        $this->confirmingDisable = false;

        $this->dispatch('refresh-navigation-dropdown');
        $this->dispatch('notify', 'Two-Factor Authentication disabled successfully!');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-authentication-setup');
    }
}
