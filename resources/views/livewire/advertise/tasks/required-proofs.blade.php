<div>
    <hr>
    <h5 class="mt-5 mb-3">Completion Proofs From Worker: </h5>
    @foreach($items as $index => $item)
        <div class="mb-2 mt-5">
            <label for="exampleFormControlTextarea{{ $index+1 }}" class="form-label">Proof {{ $index+1 }}</label>
            <div class="d-flex">
                <textarea name="requiredProofs[{{ $index }}][input]" value="old('requiredProof[{{$index}}]')" wire:model="items.{{ $index }}.input" class="form-control" id="exampleFormControlTextarea{{ $index+2 }}" rows="2" required></textarea>
                @if ($index > 0)
                    <div class="text-center p-3" wire:click.prevent="removeItem({{ $index }})"><i class="fas fa-trash"></i></div>
                @endif
                
            </div>
        </div> 
            <div class="form-floating" style="width: 300px">
                <select class="form-select" name="requiredProofs[{{ $index }}][type]" required>
                    <option value="">Select Proof {{ $index+1 }} Type</option>
                    <option value="1">Text</option>
                    <option value="2">Image/Screenshot</option>
                </select>
                <label for="floatingSelect">Proof Type</label>
            </div>
    @endforeach
    <div class="d-grid gap-2 mt-2 mb-2">
        <button wire:click.prevent="addItem" class="btn btn-outline-primary">Add More</button>
    </div>
</div>
