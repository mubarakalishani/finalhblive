@extends('layouts.afterlogin')

@section('content')
<div class="container-fluid">
    <div class="mt-3 mb-5">
        <h3>Welcome {{auth()->user()->username}}</h3>
        <p>Your Level: <b>Starter</b> <a href="/guides-and-announcements/1" target="_blank"><i class="fa fa-info-circle" aria-hidden="true"></i></a></p>
        @if (\App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 3)->count() > 0)
          <div class="alert alert-danger" role="alert">
            You have <b>{{\App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 3)->count()}}</b> tasks asked to resubmit by the employer, kindly check your <a href="/history/jobs">history</a> and resubmit them within 3 days from the last updated time.
          </div> 
        @endif
        @if (\App\Models\TaskDispute::where('employer_id', auth()->user()->id)->where('status', 0)->count() > 0)
          <div class="alert alert-danger" role="alert">
            You have <b>{{ \App\Models\TaskDispute::where('employer_id', auth()->user()->id)->where('status', 0)->count() }}</b> tasks disputes to waiting for resolution, go to <a href="/advertiser/disputes">history</a> and respond to the filed disputes within 3 days of the dispute submission date.
          </div> 
        @endif
    </div>

    <!-- dashboard status cards start -->
    <div class="dashboard-status-card">
        <div class="main-body">
          <div class="row gutters-sm">
            <div class="col">
              <div class="row gutters-sm">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                        <h5 class="d-flex align-items-center mb-3">Balance Status</h5>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Main Balance</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          ${{ auth()->user()->balance }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/withdraw">withdraw</a>
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/advertiser/transfer">Tranfer</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Advertising Balance</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          ${{ auth()->user()->deposit_balance }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/advertiser/deposit">Deposit</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Expert Level Balance</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ auth()->user()->diamond_level_balance }} 
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/guides-and-announcements/2">Learn More</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Pending Balance</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          ${{ \App\Models\SubmittedTaskProof::where('status', 0)->where('worker_id', auth()->user()->id)->sum('amount') + \App\Models\OffersAndSurveysLog::where('status', 1)->where('user_id', auth()->user()->id)->sum('reward') }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/offers-and-surveys">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Earned/Withrawn</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          ${{ auth()->user()->total_earned }} / ${{ auth()->user()->total_withdrawn }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/withdraw">View all</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="d-flex align-items-center mb-3">Micro Tasks Status</h5>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Total Submitted</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->count() }} 
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Approved+Paid</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 1)->count() }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Rejected Task</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 2)->count() }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Pending Approval</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ \App\Models\SubmittedTaskProof::where('worker_id', auth()->user()->id)->where('status', 0)->count() }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/jobs">View all</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="d-flex align-items-center mb-3">All Jobs</h5>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Total Offers</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ auth()->user()->total_offers_completed }} 
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/offers-and-surveys">view all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">PTC Completed</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ auth()->user()->total_ptc_completed }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/offers-and-surveys">view all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Faucet Completed</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ auth()->user()->total_faucet_completed }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/offers-and-surveys">view all</a>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col">
                          <div class="mb-0 dashboard-data-head">Shorterlinks Completed</div>
                        </div>
                        <div class="col text-secondary currency-style">
                          {{ auth()->user()->total_shortlinks_completed }}
                        </div>
                        <div class="col text-secondary view-all-btn">
                          <a href="/history/offers-and-surveys">view all</a>
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