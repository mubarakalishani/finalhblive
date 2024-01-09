
<div>
    <p>{{ Auth::user()->name }}</p>
    <p>available Advertising Balance : {{ Auth::user()->deposit_balance }}</p>
    {!! app('captcha')->display(['data-type' => 'audio']) !!}
    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@elseif (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <table class="table">
        <thead>
            <tr>
                <th scope="col">s#</th>
                <th scope="col">Text Proof</th>
                <th scope="col">Image Proof</th>
                <th scope="col">Approve/Reject</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($submittedProofs as $submittedProof => $proof)
                <tr>
                    <th scope="row">{{ $proof->id }}</th>
                    <td>
                        @foreach ($proof->textProofs as $textProof)
                            <p>{{ $textProof->proof_text }}</p>
                        @endforeach
                        <p>amount: {{ $proof->amount }}</p>
                        <p>status : @if ($proof->status == 0)pending @else {{ $proof->status }}
                            @endif
                        </p>
                    </td>
                    <td>
                        @foreach ($proof->imageProofs as $imageProof)
                            <img width="200px" src="{{ asset('storage/proofs/' . $imageProof->url) }}" alt="Proof Image">
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-success m-1" wire:click.prevent="approveProof({{ $proof->id }})">Approve</button>
                        <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="rejection" data-bs-proof-id="{{ $proof->id }}">Reject</button>
                        <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="revision" data-bs-proof-id="{{ $proof->id }}">Ask For Revision</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Reason: </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" wire:submit.prevent="submitEmployerComment" id="commentemployer">
                <input type="text" class="form-control" id="rejectOrRevision" wire:model.lazy="rejectOrRevision" autofocus>
                <input type="text" class="form-control proof-id" wire:model.lazy="proofId" autofocus>
              
              <div class="mb-3">
                  <label for="reasonSelected">Select Rejection/Revision Reason: </label>
                <select name="reasonSelected" class="form-select" aria-label="Default select example" wire:model.live='reasonSelected' required>
                  <option value="">Select the reason: </option>
                    @foreach ($availableRejectionReasons as $reason)
                        <option value="{{ $reason->reason }}">{{ $reason->reason }}</option>
                    @endforeach
                </select>
                @error('reasonSelected') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
              <div class="mb-3">
                <label for="reasonExplained" class="col-form-label">Explain Rejection/Revision Reason:</label>
                <textarea name="reasonExplained" class="form-control" id="message-text" wire:model.live="reasonExplained"></textarea>
                @error('reasonExplained') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="commentemployer" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
          </div>
        </div>
      </div>
    </div>
  
</div>