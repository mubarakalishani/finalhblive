@extends('layouts.afterlogin')

@section('content')
<div class="container-fluid">
    <div class="mt-3 mb-5">
        <h3>Welcome {{auth()->user()->username}}</h3>
    </div>

    <!-- dashboard status cards start -->
    <div class="dashboard-status-card">
        <div class="main-body">
          <div class="row gutters-sm">
            <div class="col">
              <div class="row gutters-sm">
                <div class="col-sm-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                        <h5 class="d-flex align-items-center mb-3">Balance Status</h5>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Main/Advertising Balance</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          ${{ auth()->user()->balance }} <a href="/withdraw">withdraw</a>
                           / ${{ auth()->user()->deposit_balance }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Expert Level Balance</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ auth()->user()->diamond_level_balance }} <a href="/">Learn More</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Pending Balance</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          ${{ \App\Models\WithdrawalHistory::where('status', 0)->where('user_id', auth()->user()->id)->sum('amount_after_fee') }}
                           <a href="/withdraw">View</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Total withdrawn</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          ${{ \App\Models\WithdrawalHistory::where('status', 1)->where('user_id', auth()->user()->id)->sum('amount_after_fee') }} 
                          <a href="/withdraw">View</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="d-flex align-items-center mb-3">Micro Tasks Status</h5>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Total Submitted</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->count() }} 
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Approved+Paid</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 1)->count() }}
                           <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Rejected</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 2)->count() }}
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Pending Approval</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 0)->count() }}
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="d-flex align-items-center mb-3">All Jobs</h5>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Total Offer/Surveys Completed</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ auth()->user()->total_offers_completed }} 
                          <a href="/history/offers-and-surveys">view all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Total PTC Completed</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ auth()->user()->total_ptc_completed }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Total Faucet Completed</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ auth()->user()->total_faucet_completed }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-6">
                          <h6 class="mb-0">Total Shorterlinks Completed</h6>
                        </div>
                        <div class="col-sm-6 text-secondary view-all-btn">
                          {{ auth()->user()->total_shortlinks_completed }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- user dashboard start -->
    <div class="page-content">

        <div class="most-popular-1">
        <div id="view-all-btn">
          <div class="results-bar d-flex align-items-center justify-content-between">
            <div class="heading-section-custom">
              <h4><em>Offers And Surveys</em> Providers</h4>
            </div>
            {{-- <div class="d-flex">
              <div class="ml-4">
                <a href="all-offers.html">View all</a>
              </div>
            </div> --}}
          </div>
        </div>
        <div class="row">
            <div class="splide splide4">
                <div class="splide__track">
                    <div class="splide__list">
                      @foreach ($offerwalls as $offerwall)
                        <div class="col-lg-2 splide__slide m-0" style="min-width:150px;">
                            <a @if($offerwall->is_target_blank !=0 ) target="_blank" @endif
                              class="offerwall-button" data-toggle="modal" data-target="#myModal" data-header="{{ $offerwall->name }}" data-url="{{ $offerwall->url }}">
                              <div class="item inner-item">
                                <img src="{{ $offerwall->image_url }}" width="150px" alt="{{ $offerwall->name }}">
                                <h4>{{ $offerwall->name }}<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Available</span></h4>
                              </div>
                            </a>
                        </div>
                      @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="most-popular-1">
            <div class="">
              <div id="view-all-btn">
                  <div class="results-bar d-flex align-items-center justify-content-between">
                    <div class="heading-section-custom">
                      <h4><em>Ptc Ads</em> Available</h4>
                    </div>
                    <div class="d-flex">
                      <div class="ml-4">
                        <a href="/views/iframe">View all</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="splide splide1">
                        <div class="splide__track">
                            <div class="splide__list">
                                @foreach ($availableIframePtcAds as $ad)
                                @if (!$ad->totalMinutesDifference || $ad->totalMinutesDifference > ($ad->revision_interval * 60))
                                  <div class="col-sm-3 splide__slide m-0">
                                      <div class="">
                                          <div class="item inner-item">
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
                                                <a href="/views/iframe/{{ $ad->unique_id }}" target="_blank"
                                                    class="form-control btn btn-primary">View ads</a>
                                            </div>
                                          </div>
                                      </div>
                                  </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="most-popular-1">
            <div id="view-all-btn">
              <div class="results-bar d-flex align-items-center justify-content-between">
                <div class="heading-section-custom">
                  <h4><em>Most Popular</em> Shortlinks</h4>
                </div>
                <div class="d-flex">
                  <div class="ml-4">
                    <a href="/shortlinks">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide6">
                    <div class="splide__track">
                        <div class="splide__list">
                          @foreach ($shortLinks as $shortLink)
                          @if ($shortLink->remaining_views <= $shortLink->views_per_day)
                            <div class="col-sm-3 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
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
                                        @if ($shortLink->remaining_views <= $shortLink->views_per_day)
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
                                </div>
                            </div>
                          @endif
                          @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- user dashboard END -->
    <!-- The Modal -->
    <div class="modal fade" id="myModal" data-backdrop="false" data-keyboard="false">
      <div class="modal-dialog modal-lg modal-full-width">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <h4 class="modal-title" id="modalHeader">Website Loading </h4>
                  <span>
                      <a id="modalFullPage" href="#" target="_blank">
                          <i class="fas fa-external-link-alt"></i>
                      </a>
                  </span>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Loader container within the modal -->
              <div class="loader-container">
                  <div class="loader"></div>
              </div>

              <!-- Modal body -->
              <iframe id="modalIframe" style="width: 100%; height: 90vh;" onload="hideLoader()"></iframe>

              <!-- Modal footer -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
    </div>
</div>
<script>
  // JavaScript to set the iframe src, modal header, and open the external link in a new tab when a button is clicked
  $('.offerwall-button').on('click', function() {
        var url = $(this).data('url');
        var header = $(this).data('header');

        // Show the loader within the modal
        showLoader();

        $('#modalFullPage').attr('href', url); // Set the href attribute of the anchor tag
        $('#modalIframe').attr('src', url);
        $('#modalHeader').text(header);
    });

    // Function to show the loader within the modal
    function showLoader() {
        $('.loader-container').show();
    }

    // Function to hide the loader within the modal
    function hideLoader() {
        $('.loader-container').hide();
    }
</script>
@endsection