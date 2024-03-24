@extends('layouts.afterlogin')
@section('content')
@php
    $countIframe = 0;
@endphp
@foreach ($availableIframePtcAds as $ad)
    @if (!$ad->totalSecondsDifference || $ad->totalSecondsDifference > ($ad->revision_interval * 60 * 60))
        @if ($ad->views_completed < $ad->views_needed)
            @php
                $countIframe++;
            @endphp
        @endif    
    @endif
@endforeach


@php
    $countWindows = 0;
@endphp
@foreach ($availableWindowPtcAds as $ad)
    @if (!$ad->totalSecondsDifference || $ad->totalSecondsDifference > ($ad->revision_interval * 60 * 60))
        @if ($ad->views_completed < $ad->views_needed)
            @php
                $countWindows++;
            @endphp
        @endif    
    @endif
@endforeach
    <div class="container-fluid">
        <div class="most-popular all-ptc-ads-page">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="/views/iframe">Iframe <span class="badge bg-primary">{{ $countIframe }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/views/window">Windows <span class="badge bg-primary">{{ $countWindows }}</span></a>
                  </li>
                  {{-- <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/views/youtube">Youtube</a>
                  </li> --}}
            </ul>

            <div class="row">
                @foreach ($availableIframePtcAds as $ad)
                    @if (!$ad->totalSecondsDifference || $ad->totalSecondsDifference > ($ad->revision_interval * 60 * 60))
                        @if($ad->views_completed < $ad->views_needed)
                            <div class="col-lg-4 col-md-6 col-sm-12 mt-4 pt-2" id="ad{{ $ad->id }}">
                            <div class="card-body ">
                                <div class="ads-para-description text-center" style="height: 110px;">
                                    <h6>{{ $ad->title }}</h6>
                                    <span style="font-size: 0.8rem !important">{{ $ad->description }}</span>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4">
                                        <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="seconds you have to watch this ad before getting credited">
                                            <i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $ad->seconds }}
                                            sec
                                        </span>
                                    </div>
                                    <div class="col-4">
                                        <span class="text-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="The Amount You will earn for viewing this listing">
                                            <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{
                                            $ad->reward_per_view }}
                                        </span>
                                    </div>

                                    <div class="col-4">
                                        <span class="text-success" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="This is the time after which you can rewatch this ad.">
                                            <i class="fa-solid fa-arrows-rotate"></i> {{ $ad->revision_interval
                                            }}hrs
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="/views/iframe/{{ $ad->unique_id }}" onclick="adClicked('ad{{ $ad->id }}')" target="_blank"
                                    class="form-control btn btn-primary">View ads</a>
                            </div>
                            </div>
                        @endif    
                    @endif
                @endforeach
            </div>

            <div class="row">
                @foreach ($availableIframePtcAds as $ad)
                    @if ($ad->totalSecondsDifference && $ad->totalSecondsDifference < ($ad->revision_interval * 60 * 60))
                        <div class="col-lg-4 col-md-6 col-sm-12 mt-4 pt-2">
                            <div class="ptc-item">
                                <div class="card-body ">
                                <div class="ads-para-description text-center" style="height: 100px;">
                                    <h6>{{ $ad->title }}</h6>
                                    <span>{{ $ad->description }}</span>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4">
                                        <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="average time required to solve this shortlink">
                                            <i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $ad->seconds }}
                                            sec
                                        </span>
                                    </div>
                                    <div class="col-4">
                                        <span class="text-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="The Amount You will earn for viewing this listing">
                                            <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{
                                            $ad->reward_per_view }}
                                        </span>
                                    </div>

                                    <div class="col-4">
                                        <span class="text-success" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="This is the time after which you can rewatch this ad.">
                                            <i class="fa-solid fa-arrows-rotate"></i> {{ $ad->revision_interval
                                            }}hrs
                                        </span>
                                    </div>
                                </div>
                                </div>
                                <div class="card-footer">
                                <button
                                    class="form-control btn btn-secondary" disabled>wait {{ $ad->remaining_time }}</button>
                                </div>
                                </div>
                            </div>
                    @endif
                @endforeach
            </div>
        
        </div>
    </div>

    <script>
        function adClicked(id){
            document.getElementById(''+id+'').style.display = 'none';
        }
    </script>

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
