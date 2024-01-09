<div class="all-history-page">
    <!-- ***** My campaigns Start ***** -->
    <div class="main-profile mycampaigns-page">
        <div class="col-lg-12">
            <div class="heading-section">
                <h4>Manage Ptc Campaigns</h4>
            </div>
            
            <div class="results-bar d-flex align-items-center justify-content-between mb-4">

                <div class="d-flex results-bar__search">
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Search here" value="">
                    <select class="form-select mx-2" id="floatingSelect" aria-label="Floating label select example" wire:model.live="status">
                        <option value="">Select Status</option>
                        <option value="0">Pending Approval</option>
                        <option value="1">Approved/Active</option>
                        <option value="2">Declined</option>
                        <option value="3">Paused</option>
                        <option value="4">Completed</option>
                        <option value="5">Paused by Admin</option>
                        <option value="6">Stopped by Admin</option>
                      </select>
                </div>
            </div>

            <!-- top area-before-table -->
            <div class="table-responsive-lg">
                <table class="table table-big table-hover table-middle">
                    <thead>
                        <tr class="table-row">
                            <th scope="col" class="white-space-pre text-center">Status</th>

                            <th scope="col" class="white-space-pre">Title</th>
                            <th scope="col" class="white-space-pre">description</th>
                            <th scope="col" class="white-space-pre">Url</th>
                            <th scope="col" class="white-space-pre text-center">clicks</th>
                            <th scope="col" class="white-space-pre text-center">Spent/Remaining</th>
                            <th scope="col" class="white-space-pre text-center">Pause/Resume</th>
                            {{-- <th scope="col" class="white-space-pre text-center">view</th> --}}
                        </tr>
                    </thead>
                    <tbody id="jobs-list">
                        @foreach ($ptcAds as $ad)
                        
                        <tr class="table-row clickable">
                            <td scope="row" class="table-cell-status text-center">
                                @if ($ad->status == 0)
                                <span class="badge rounded-pill text-bg-warning p-2">Pending Approval</span>
                                @elseif ($ad->status == 1)
                                <span class="badge rounded-pill text-bg-success p-2">Active</span>
                                @elseif ($ad->status == 2)
                                <span class="badge rounded-pill text-bg-danger p-2">Declined</span>
                                @elseif ($ad->status == 3)
                                <span class="badge rounded-pill text-bg-secondary p-2">Paused</span>
                                @elseif ($ad->status == 4)
                                <span class="badge rounded-pill text-bg-info p-2">Completed</span>
                                @elseif ($ad->status == 5)
                                <span class="badge rounded-pill text-bg-dark p-2">Admin Paused</span>
                                @elseif ($ad->status == 6)
                                <span class="badge rounded-pill text-bg-danger p-2">Admin stopped</span>    
                                @endif
                            </td>

                            <td class="table-cell-name">
                                {{-- <a href="/advertiser/ptc/campaign/{{ $ad->id }}">{{ $ad->title }}</a> --}}
                                <span>{{ $ad->title }}</span>
                            </td>
                            <td class="table-cell-name"><span>{{ $ad->description }}</span></td>
                            <td class="table-cell-name"><span><a href="{{ $ad->url }}" target="_blank">{{ $ad->url }}</span></td>
                            <td class="table-cell-rated text-center">{{ $ad->views_completed }} / {{ $ad->views_needed }}</td>
                            <td class="table-cell-rated text-center">
                                <b>${{ $ad->views_completed * $ad->reward_per_view }}<b> / 
                                @if ($editBudget && $adId == $ad->id)
                                    <div  class="input-group">
                                        <input type="number" wire:model.live="budgetToAdd" class="form-control" placeholder="0.00">
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">save</button>
                                    </div>
                                @else${{ $ad->views_needed * $ad->reward_per_view }} <a wire:click="showEditBudget('{{ $ad->id }}')"><i class="fa fa-plus-circle text-success"></i></a>@endif</b>
                            </td>
                            <td class="table-cell-settings p-0 text-center">
                                <a wire:click="pauseResume('{{ $ad->id }}')">
                                    @if ($ad->status == 1)
                                        <i class="fa fa-pause side-icons text-primary" aria-hidden="true"></i>
                                    @elseif($ad->status == 3)
                                        <i class="fa fa-play side-icons text-primary" aria-hidden="true"></i>
                                    @endif
                                </a>
                            </td>
                            {{-- <td class="table-cell-settings p-0 text-center">
                                <a href="/advertiser/ptc/campaign/{{ $ad->id }}">
                                    <i class="fa fa-eye side-icons" aria-hidden="true"></i>
                                </a>
                            </td> --}}
                            
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex">
                {{ $ptcAds->links() }}
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
            
            <!-- demo -->
        </div>
    </div>
    <!-- My campaigns end -->
  
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Are You Sure?</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>You are going to add <b>${{ $budgetToAdd }}</b> to the selected Campaign. Click Confirm to approve this action or click cancel to cancel this</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" wire:click="submitUpdatedBudget" class="btn btn-primary">Confirm</button>
          </div>
        </div>
      </div>
    </div>
</div>

