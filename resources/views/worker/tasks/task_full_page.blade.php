<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/891a7151bf.js" crossorigin="anonymous"></script>

    <title>Create Task</title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-6 offset-3">
                <h2>Title: {{ $task->title }}</h2>
                <h2>id: {{ $task->id }}</h2>
                <p>Category: {{ $category->name }}</p>
                <p>Subcategory: {{ $subCategory->name}}</p>
                <p>{{ $reward }}</p>

                <h6>Task Instructions</h6>
                <ol>
                    @foreach($task->stepDetails as $step)
                        <li>{{ $step->step_details }}</li>
                    @endforeach
                </ol>

                <h6>Proofs Required</h6>
                <ol>
                    {{-- use screenshot or text icon at the end of the required step --}}
                    @foreach($task->requiredProofs as $proof)
                        <li>{{ $proof->proof_text }}</li>
                    @endforeach
                </ol>


                <form action="{{ route('worker.submit_task', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @foreach($task->requiredProofs as $proof)
                        @if ( $proof->proof_type == 1)
                            <div class="form-floating mt-2 mb-2">
                                <textarea name="text_proofs[{{ $proof->proof_no }}]" class="form-control" placeholder="Input Your Text Proof Here..." id="floatingTextarea2" style="height: 100px"></textarea>
                                <label for="floatingTextarea2">Proof {{ $proof->proof_no }} : {{ $proof->proof_text }}</label>
                            </div>
                        @else
                        <div class="mb-2 mt-2">
                            <label for="formFile" class="form-label">Proof {{ $proof->proof_no }} : {{ $proof->proof_text }}</label>
                            <input name="image_proofs[{{ $proof->proof_no }}]" class="form-control" type="file" id="formFile">
                          </div>
                        @endif
                    @endforeach

                    <div class="d-grid gap-2 mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  
</body>
</html>