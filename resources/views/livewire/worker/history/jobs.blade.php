<div>

    <div class="container-fluid">
                    

        <!-- all games start -->
        <div class="all-history-page">
            <div class="container py-4">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="/history/jobs">Jobs History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/history/offers-and-surveys">Offers & Surveys History</span></a>
                    </li>
                </ul>
                <div class="pt-3">
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
                                    <div class="d-flex flex-wrap">
                                        <select wire:model.live="status">
                                              <option id="all" value="">All</option>
                                              <option id="pending" value="0">Pending</option>
                                              <option id="approved" value="1">Approved</option>
                                              <option id="rejected" value="2">Rejected</option>
                                              <option id="resubmit requested" value="3">Resubmit Requested</option>
                                              <option id="resubmitted" value="4">Resubmited</option>
                                              <option id="dispute-filed" value="5">Dispute Filed</option>
                                              <option id="dispute-filed" value="6">Dispute Rejected</option>
                                          </select>
                                    </div>
                                </div>
                                <!-- top area-before-table end-->

                                <div class="table-responsive-lg" style="overflow-x: auto;">
                                    <table class="table table-big table-hover table-middle table-striped">
                                        <thead>
                                            <tr class="table-row" >
                                                <th scope="col" class="white-space-pre">Job ID</th>
                                                <th scope="col" class="white-space-pre text-center">Job Title</th>
                                                <th scope="col" class="white-space-pre text-center">Reward</th>
                                                <th scope="col" class="white-space-pre text-center">Status</th>
                                                <th scope="col" class="white-space-pre text-center">Submitted</th>
                                                <th scope="col" class="white-space-pre text-center">Last Updated</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jobs-list">
                                            @foreach ($proofs as $proof)
                                                <tr class="table-row clickable">
                                            <!-- <td scope="row" class="table-cell-status text-center">
                                              <i class="fa fa-times" aria-hidden="true"></i>
                                            </td> -->
                                            
                                            <td class="table-cell-name">
                                                <a href="/jobs/submitted/{{ $proof->task->id }}">
                                                    {{ $proof->task->id }}
                                                </a>
                                            </td>
                                            <td scope="row" class="table-cell-status text-center">
                                                <a href="/jobs/submitted/{{ $proof->task->id }}">
                                                    {{ $proof->task->title }}
                                                </a> 
                                            </td>
                                            <td class="table-cell-progress text-center">{{ $proof->amount }}</td>
                                            <td class="table-cell-settings text-center">
                                                <span class="badge text-bg-primary py-2 px-2 rounded-pill
                                                    @if($proof->status == 0)
                                                    bg-warning
                                                    @elseif($proof->status == 1)
                                                        bg-success
                                                    @elseif($proof->status == 2)
                                                        bg-danger
                                                    @elseif($proof->status == 3 || $proof->status == 10)
                                                        bg-info
                                                    @elseif($proof->status == 4)
                                                        bg-warning
                                                    @elseif($proof->status == 5)
                                                        bg-info
                                                    @elseif($proof->status == 6 || $proof->status == 7 || $proof->status == 8 || $proof->status == 9)
                                                        bg-danger
                                                    @endif">
                                                    @if($proof->status == 0)
                                                        Pending
                                                    @elseif($proof->status == 1)
                                                        Approved
                                                    @elseif($proof->status == 2)
                                                        Rejected
                                                    @elseif($proof->status == 3)
                                                        Requested Resubmit
                                                    @elseif($proof->status == 4)
                                                        Pending
                                                    @elseif($proof->status == 5)
                                                        Dispute Filed
                                                    @elseif($proof->status == 6)
                                                        Dispute Rejected
                                                    @elseif($proof->status == 7) 
                                                        Resubmission time Expired
                                                    @elseif($proof->status == 8)
                                                        Rejected: Dispute time Expired
                                                    @elseif($proof->status == 9)
                                                        Rejected: Appeal to Admin Time expired
                                                    @elseif($proof->status == 10)
                                                        Appeal Filed to Admin           
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="table-cell-rated text-center">{{ $proof->created_at->diffForHumans() }}</td>
                                            <td class="table-cell-rated text-center">{{ $proof->updated_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach  
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    {{ $proofs->links() }}
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
</div>