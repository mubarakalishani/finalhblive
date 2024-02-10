<div>

    <div class="container-fluid">
                    

        <!-- all games start -->
        <div class="all-history-page">
            <div class="container">
                <div class="pt-3">
            <h4><em>Referrals</em></h4>
        </div>
        <div class="text-center">
            {{-- <h1>Refer a friend and get 10% off your next purchase!</h1>
            <p>Share your referral link with your friends and they will get a 5% discount on their first order. You will also get a 10% coupon code when they make a purchase.</p> --}}
            <input id="referral-link" type="text" class="form-control" value="{{url('/'.'?ref='.auth()->user()->username)}}" readonly><br>
            <button id="copy-button" class="btn btn-outline-primary" onclick="copyLink()">Copy link</button>
          </div>
              <div class="row align-items-end mb-4 pb-2">
                  <div class="col-md-8">
                  </div><!--end col-->
                  
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="history-data mt-3">
                            <div>
                                
                                <div class="results-bar d-flex align-items-center justify-content-between flex-wrap mb-2">
                                    <div class="d-flex results-bar__search">
                                        <input type="text" id="search-term" class="form-control" placeholder="search here" wire:model.live.debounce.300ms="search">
                                    </div>
                                  {{-- <div class="d-flex flex-wrap">
                                      <select wire:model.live="status">
                                            <option value="">All</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Approved</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                  </div> --}}
                                </div>
                                <!-- top area-before-table end-->

                                <div class="table-responsive-lg">
                                    <table class="table table-big table-hover table-middle table-striped">
                                      <thead>
                                        <tr class="table-row" >
                                          <!-- <th scope="col" class="white-space-pre text-center">Status</th> -->

                                          <th scope="col" class="white-space-pre">Username</th>
                                          <th scope="col" class="white-space-pre text-center">Country</th>
                                          <th scope="col" class="white-space-pre text-center">Joined Date</th>
                                          <th scope="col" class="white-space-pre text-center">User Earned</th>
                                        </tr>
                                      </thead>
                                      <tbody id="jobs-list">
                                        @foreach ($referrals as $referral)
                                          <tr class="table-row clickable">
                                            <!-- <td scope="row" class="table-cell-status text-center">
                                              <i class="fa fa-times" aria-hidden="true"></i>
                                            </td> -->
                                            
                                            <td class="table-cell-name">
                                                {{ $referral->username }}
                                            </td>
                                            <td scope="row" class="table-cell-status text-center">
                                                {{ $referral->country }}
                                            </td>
                                            <td class="table-cell-rated text-center">{{ $referral->created_at->diffForHumans()  }}</td>
                                            <td class="table-cell-rated text-center">${{ $referral->total_earned }}</td>
                                          </tr>
                                        @endforeach  
                                      </tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    {{ $referrals->links() }}
                                </div>
                                <div class="d-flex flex-wrap mt-2">
                                    <select wire:model.live="perPage">
                                          <option value="5">5</option>
                                          <option value="10">10</option>
                                          <option value="25">25</option>
                                          <option value="50">50</option>
                                          <option value="100">100</option>
                                      </select>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
    <script>
    function copyLink() {
        // Get the referral link input element
        var link = document.getElementById("referral-link");
        // Select the link text
        link.select();
        link.setSelectionRange(0, 99999); // For mobile devices
        // Copy the link text to the clipboard
        document.execCommand("copy");
        // Change the button text to show feedback
        var button = document.getElementById("copy-button");
        button.innerHTML = "Copied!";
        // Reset the button text after 3 seconds
        setTimeout(function() {
          button.innerHTML = "Copy link";
        }, 3000);
      }
      
    </script> 
</div>
