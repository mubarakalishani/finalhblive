@extends('layouts.afterlogin')
@section('content')

@if ($tab=='pending')
  @livewire('advertise.tasks.campaing-detail-with-pending-proofs', ['taskId' => $taskId])
@elseif ($tab=='approved')
  @livewire('advertise.tasks.campaing-detail-with-approved-proofs', ['taskId' => $taskId])
@elseif ($tab=='rejected')
  @livewire('advertise.tasks.campaing-detail-with-rejected-proofs', ['taskId' => $taskId])
@elseif ($tab=='revision')
  @livewire('advertise.tasks.campaing-detail-with-revision-asked-proofs', ['taskId' => $taskId])
@endif


 <script>
    const exampleModal = document.getElementById('exampleModal');
    if (exampleModal) {
      exampleModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const rejectOrRevision = button.getAttribute('data-bs-whatever');
        const proofId = button.getAttribute('data-bs-proof-id');

        Livewire.dispatch('updateModalContent', {rejectOrRevision , proofId});
        console.log(rejectOrRevision, proofId);

      });
    }
  </script>
@endsection
                
            