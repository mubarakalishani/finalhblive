<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/891a7151bf.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Faucet</title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-3 offset-3">
                <p>Amount : {{ $faucet_claim_amount }}</p>
                <p>Time : {{ $faucet_claim_time }}</p>
                <p>Last Claim: {{ $timeAgo }} </p>
                <form method="POST" action=" {{ route( 'worker.claim_faucet' ) }} ">
                    @csrf
                    <div class="mb-3">
                        {!! Captcha::display() !!}
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" type="button">Claim</button>
                    </div>
                  </form>
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <script>
            Swal.fire({
                title: "Good job!",
                text: "{{ session('message')}} ",
                icon: "success"
            });
        </script>
    @endif 
    
    @if(session()->has('error'))
        <script>
            Swal.fire({
                title: "Oops!",
                text: "{{ session('error')}} ",
                icon: "error"
            });
        </script>
    @endif 
   
    
</body>
</html>
