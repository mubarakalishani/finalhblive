@extends('layouts.afterlogin')
@section('content')

<div class="container-fluid">
  <div class="row">
    @foreach ($shortLinks as $shortLink)
    @if ($shortLink->remaining_views <= $shortLink->views_per_day)
    <div class="col-lg-3 col-md-6 col-12 mt-4 pt-2">
      <div class="card border-0 bg-light rounded shadow">
        <div class="card-header">
          <div class="row">
            <div class="col-9">
              <h6>{{ $shortLink->name }}</h6>
            </div>
            <div class="col-3">
              <span class="badge rounded-pill bg-danger float-md-end mb-3 mb-sm-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Report Any problem with this shortlink">
                <a href="#" class="text-light">Report</a>
              </span>
            </div>
          </div>
        </div>
        <div class="card-body ">
          <div class="row mt-3 mb-3">

            <div class="col-4">
              <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right" title="average time required to solve this shortlink">
                <i class="fa-solid fa-clock" aria-hidden="true"></i> ~{{ $shortLink->min_seconds }} sec
              </span>
            </div>
            <div class="col-4">
              <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="The Amount You will earn after completing this shortlink">
                <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{ $shortLink->reward }}
              </span>
            </div>

            <div class="col-4">
              <span class="text-success" data-bs-toggle="tooltip" data-bs-placement="right" title="left side is views remaining, and right side is the total views allowed in 24h.">
                <i class="fa-solid fa-eye" aria-hidden="true"></i> {{ $shortLink->remaining_views }}/{{ $shortLink->views_per_day }}
              </span>
            </div>
          </div>
        </div>
        @if ($shortLink->remaining_views >= $shortLink->views_per_day)
          <div class=" mb-4 text-center">
            <a href="/shortlink/{{ $shortLink->unique_id }}" class="btn btn-primary" target="_blank">Claim</a>
          </div>
        @else
          <div class=" mb-4 text-center">
            <span>wait {{ $shortLink->remaining_time }} before next claim</span>
          </div>
        @endif
        
      </div>
    </div>
    @endif
    @endforeach


    @foreach ($shortLinks as $shortLink)
    @if ($shortLink->remaining_views == 0)
    <div class="col-lg-3 col-md-6 col-12 mt-4 pt-2">
      <div class="card border-0 bg-light rounded shadow">
        <div class="card-header">
          <div class="row">
            <div class="col-9">
              <h6>{{ $shortLink->name }}</h6>
            </div>
            <div class="col-3">
              <span class="badge rounded-pill bg-danger float-md-end mb-3 mb-sm-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Report Any problem with this shortlink">
                <a href="#" class="text-light">Report</a>
              </span>
            </div>
          </div>
        </div>
        <div class="card-body ">
          <div class="row mt-3 mb-3">

            <div class="col-4">
              <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right" title="average time required to solve this shortlink">
                <i class="fa-solid fa-clock" aria-hidden="true"></i> ~{{ $shortLink->min_seconds }} sec
              </span>
            </div>
            <div class="col-4">
              <span class="text-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="The Amount You will earn after completing this shortlink">
                <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{ $shortLink->reward }}
              </span>
            </div>

            <div class="col-4">
              <span class="text-success" data-bs-toggle="tooltip" data-bs-placement="right" title="left side is views remaining, and right side is the total views allowed in 24h.">
                <i class="fa-solid fa-eye" aria-hidden="true"></i> {{ $shortLink->remaining_views }}/{{ $shortLink->views_per_day }}
              </span>
            </div>
          </div>
        </div>
        @if ($shortLink->remaining_views >= $shortLink->views_per_day)
          <div class=" mb-4 text-center">
            <a href="/shortlink/{{ $shortLink->unique_id }}" class="btn btn-primary" target="_blank">Claim</a>
          </div>
        @else
          <div class=" mb-4 text-center">
            <span>wait {{ $shortLink->remaining_time }} before next claim</span>
          </div>
        @endif
        
      </div>
    </div>
    @endif
    @endforeach
  </div>
</div>

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