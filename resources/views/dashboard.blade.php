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
            <div class="">
              <div id="view-all-btn">
                  <div class="results-bar d-flex align-items-center justify-content-between">
                    <div class="heading-section-custom">
                      <h4><em>Ptc Ads</em> Available</h4>
                    </div>
                    <div class="d-flex">
                      <div class="ml-4">
                        <a href="login.html">View all</a>
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
                                  <div class="col-sm-4 splide__slide m-0">
                                      <div class="">
                                          <div class="item inner-item">
                                            <div class="ads-para-description text-center" style="height: 100px;">
                                                <h6>{{ $ad->title }}</h6>
                                                <span style="font-size: 0.8rem !important">{{ $ad->description }}</span>
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
                                      </div>
                                  </div>
                                @endif
                                @endoforeach
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
                  <h4><em>Micro</em> Jobs</h4>
                </div>
                <div class="d-flex">
                  <div class="ml-4">
                    <a href="jobspage.html">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide2">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
                                      <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                      <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/lot.png" alt="">
                                    <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                    <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                    <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/agm.png" alt="">
                                    <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
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
                  <h4><em>Most Popular</em> Surveys</h4>
                </div>
                <div class="d-flex">
                  <div class="ml-4">
                    <a href="all-surveys.html">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide3">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
                                      <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                      <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/lot.png" alt="">
                                    <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                    <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                    <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/agm.png" alt="">
                                    <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
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
                  <h4><em>Offers</em> Providers</h4>
                </div>
                <div class="d-flex">
                  <div class="ml-4">
                    <a href="all-offers.html">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide4">
                    <div class="splide__track">
                        <div class="splide__list">
                          @foreach ($offerwalls as $offerwall)
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                <a @if($offerwall->is_target_blank !=0 ) target="_blank" @endif
                                  class="offerwall-button" data-toggle="modal" data-target="#myModal" data-header="{{ $offerwall->name }}" data-url="{{ $offerwall->url }}">
                                  <div class="item inner-item">
                                    <img src="{{ $offerwall->image_url }}" alt="{{ $offerwall->name }}">
                                    <h4>{{ $offerwall->name }}<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </a>
                              </div>
                            </div>
                          @endforeach
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
                    <a href="all-shortlinks.html">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide6">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
                                      <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                      <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/lot.png" alt="">
                                    <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                    <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                    <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/agm.png" alt="">
                                    <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
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
                  <h4><em>Most Popular</em> Games</h4>
                </div>
                <div class="d-flex">
                  <div class="ml-4">
                    <a href="all-games.html">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide7">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
                                      <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                      <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/lot.png" alt="">
                                    <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                    <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                    <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/agm.png" alt="">
                                    <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
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
                  <h4><em>Most Popular</em> PTC</h4>
                </div>
                <div class="d-flex">
                  <div class="ml-4">
                    <a href="allptc-ads.html">View all</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="splide splide8">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
                                      <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                      <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/lot.png" alt="">
                                    <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                    <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                    <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/agm.png" alt="">
                                    <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                            </div>
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