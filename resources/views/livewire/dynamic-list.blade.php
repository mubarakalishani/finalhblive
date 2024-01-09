<div>
    <h5 class="mt-5 mb-3">Task Requirements Or Instructions</h5>
    <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label">Step 1</label>
        <textarea name="step[]" value="{{ old('step[]') }}" class="form-control" id="exampleFormControlTextarea1" rows="2" required></textarea>
    </div>
    @foreach($items as $index => $item)
            <label for="exampleFormControlTextarea{{ $index+2 }}" class="form-label">Step {{ $index+2 }}</label>
            <div class="d-flex">
                <textarea name="step[]" value="{{ old('step[]')}}" wire:model="items.{{ $index }}.input" class="form-control" id="exampleFormControlTextarea{{ $index+2 }}" rows="2" required></textarea>
                <div class="text-center p-3" wire:click.prevent="removeItem({{ $index }})"><i class="fas fa-trash"></i></div>
            </div>
    @endforeach
    <div class="d-grid gap-2 mt-2 mb-2">
        <button wire:click.prevent="addItem" class="btn btn-outline-primary">Add Steps</button>
    </div>
</div>


