<div class="container-fluid">
    <div class="all-history-page">
        <div class="container py-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="/history/jobs">Jobs History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/history/offers-and-surveys">Offers & Surveys History</span></a>
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
                                        <option value="">All</option>
                                        <option value="1">Pending</option>
                                        <option value="0">Approved</option>
                                        <option value="2">Rejected</option>
                                    </select>
                              </div>
                            </div>
                            <!-- top area-before-table end-->

                            <div class="table-responsive-lg" style="overflow-x: auto;">
                                <table class="table table-big table-hover table-middle table-striped">
                                  <thead>
                                    <tr class="table-row" >
                                      <!-- <th scope="col" class="white-space-pre text-center">Status</th> -->

                                      <th scope="col" class="white-space-pre">Provider</th>
                                      <th scope="col" class="white-space-pre text-center">Title</th>
                                      <th scope="col" class="white-space-pre text-center">Reward</th>
                                      <th scope="col" class="white-space-pre text-center" data-bs-toggle="tooltip" data-bs-placement="right" title="You will receive this amount when You will become and expert level user">Added to Expert Level</th>
                                      <th scope="col" class="white-space-pre text-center">Status</th>
                                      <th scope="col" class="white-space-pre text-center">Submitted</th>
                                      <th scope="col" class="white-space-pre text-center">Last Updated</th>
                                      <th scope="col" class="white-space-pre text-center">Remarks</th>
                                    </tr>
                                  </thead>
                                  <tbody id="jobs-list">
                                    @foreach ($histories as $history)
                                      <tr class="table-row clickable">
                                        <td class="table-cell-name">
                                          {{ $history->provider_name}}
                                        </td>
                                        <td scope="row" class="table-cell-status text-center">
                                            {{ $history->offer_name }}
                                        </td>
                                        <td class="table-cell-progress text-center">{{ $history->reward }}</td>
                                        <td class="table-cell-progress text-center" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="You will receive this amount at once, when You will become expert level user">
                                            {{ $history->added_expert_level }}
                                        </td>
                                        <td class="table-cell-settings text-center">
                                            <span class="badge text-bg-primary py-2 px-2 rounded-pill
                                                @if($history->status == 0)
                                                    bg-success
                                                @elseif($history->status == 1)
                                                    bg-warning
                                                @elseif($history->status == 2)
                                                    bg-danger
                                                @endif">
                                                @if($history->status == 0)
                                                    Approved
                                                @elseif($history->status == 1)
                                                    Pending
                                                @elseif($history->status == 2)
                                                    Reversed
                                                @endif
                                            </span>
                                        </td>
                                        <td class="table-cell-rated text-center">{{ $history->created_at->diffForHumans() }}</td>
                                        <td class="table-cell-rated text-center">{{ $history->updated_at->diffForHumans() }}</td>
                                        <td class="table-cell-rated text-center">{{ $history->remark }}</td>
                                      </tr>
                                    @endforeach  
                                  </tbody>
                                </table>
                            </div>
                            <div class="d-flex">
                                {{ $histories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
