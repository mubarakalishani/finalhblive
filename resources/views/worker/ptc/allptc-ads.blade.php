@extends('layouts.afterlogin')
@section('content')

  @livewire('worker.ptc-list')
  @if(session()->has('success'))
        <script>
            Swal.fire({
                title: "Good job!",
                text: "{{ session('success')}} ",
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
@endsection 