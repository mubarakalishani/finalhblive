@extends('layouts.afterlogin')
@section('content')
 @livewire('advertise.tasks.disputes')

 <script>
    const exampleModal = document.getElementById('exampleModal');
    if (exampleModal) {
      exampleModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const proofId = button.getAttribute('data-bs-proof-id');

        Livewire.dispatch('updateModalContent', {proofId});

      });
    }
  </script>
@endsection