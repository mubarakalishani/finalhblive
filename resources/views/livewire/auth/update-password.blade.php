<div class="col-lg-8">
    <!-- Change password card-->
    <div class="card mb-4">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            @if ($successMessage)
                <div class="alert alert-success">
                    {{ $successMessage }}
                </div>
            @endif
            <form wire:submit.prevent="updatePassword">
                <!-- Form Group (current password)-->
                <div class="mb-3">
                    <label class="small mb-1" for="currentPassword">Current Password</label>
                    <input class="form-control" id="currentPassword" type="password" placeholder="Enter current password" wire:model="currentPassword">
                    @error('currentPassword') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <!-- Form Group (new password)-->
                <div class="mb-3">
                    <label class="small mb-1" for="newPassword">New Password</label>
                    <input class="form-control" id="newPassword" type="password" placeholder="Enter new password" wire:model="password_confirmation">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <!-- Form Group (confirm password)-->
                <div class="mb-3">
                    <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                    <input class="form-control" id="confirmPassword" type="password" placeholder="Confirm new password" wire:model="password">
                    @error('passwordConfirmation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>