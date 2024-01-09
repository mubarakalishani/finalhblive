@php
    use App\Models\User;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @livewireStyles
    @livewireScripts
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/891a7151bf.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Review Task</title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-6 offset-3">
                @livewire('review-tasks', ['taskId' => $taskId])
            </div>
        </div>
    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  
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

</body>
</html>