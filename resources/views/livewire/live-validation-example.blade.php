<div>
    <div>
        <input type="text" wire:model.live="name">
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>    
</div>