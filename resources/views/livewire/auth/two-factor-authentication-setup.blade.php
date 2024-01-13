
<div>
    <!-- Enable Two-Factor Authentication -->
    <x-button wire:click="confirmEnable">
        {{ __('Enable Two-Factor Authentication') }}
    </x-button>

    <x-dialog-modal wire:model="confirmingEnable">
        <x-slot name="title">
            {{ __('Enable Two-Factor Authentication') }}
        </x-slot>

        <x-slot name="content">
            {{ __('You are enabling Two-Factor Authentication.') }}
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingEnable')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-button>

            <x-button class="ml-2" wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled">
                {{ __('Enable') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Disable Two-Factor Authentication -->
    @if (Auth::user()->two_factor_secret)
        <x-button wire:click="confirmDisable">
            {{ __('Disable Two-Factor Authentication') }}
        </x-button>

        <x-dialog-modal wire:model="confirmingDisable">
            <x-slot name="title">
                {{ __('Disable Two-Factor Authentication') }}
            </x-slot>

            <x-slot name="content">
                {{ __('You are disabling Two-Factor Authentication.') }}
            </x-slot>

            <x-slot name="footer">
                <x-button wire:click="$toggle('confirmingDisable')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-button>

                <x-button class="ml-2" wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                    {{ __('Disable') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>

