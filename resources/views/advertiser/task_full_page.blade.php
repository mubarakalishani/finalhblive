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
        <h1>Task Details</h1>

    <div class="row">
        <div class="col">
            <h2>{{ $task->title }}</h2>
            <p>Category: {{ $category->name }}</p>
            <p>Subcategory: {{ $subCategory->name}}</p>
        </div>

        <div class="col">
            <h3>Task Steps</h3>
            <ol>
                @foreach($task->stepDetails as $step)
                    <li>{{ $step->step_details }}</li>
                @endforeach
            </ol>
        </div>

        <div class="col">
            <h3>Required Proofs</h3>
            <ol>
                @foreach($task->requiredProofs as $proof)
                    <li>{{ $proof->proof_text }}</li>
                @endforeach
            </ol>    
        </div>

        <div class="col">
            <h3>Targeted Countries</h3>
            @if ($task->excludedCountries->isEmpty())
                    <p>Worldwide</p>
                @else
                {{-- Display the list of excluded countries --}}
                @foreach ($task->targetedCountries as $country)
                    <p>{{ $country->country }}</p>
                @endforeach
            @endif
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  
</body>
</html>