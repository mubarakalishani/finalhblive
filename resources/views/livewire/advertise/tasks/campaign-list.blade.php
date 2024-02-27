<div class="all-history-page">
    <!-- ***** My campaigns Start ***** -->
    <div class="main-profile mycampaigns-page">
        <div class="col-lg-12">
            <div class="heading-section">
                <h4>Manage Campaigns</h4>
            </div>
            {{-- <div class="alert alert-with-icon pw-badge-danger fade show font-weight-medium" role="alert">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <a class="password-change-link text-danger">Jobs older than 6 months are no longer available.</a>
            </div> --}}
            <div class="results-bar d-flex align-items-center justify-content-between mb-4">

                <div class="d-flex results-bar__search">
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Search here" value="">
                    <select class="form-select mx-2" id="floatingSelect" aria-label="Floating label select example" wire:model.live="status">
                        <option value="10" selected>Select Status</option>
                        <option value="0">Pending Approval</option>
                        <option value="1">Approved/Active</option>
                        <option value="2">Declined</option>
                        <option value="3">Paused</option>
                        <option value="6">Budget Exceeded</option>
                        <option value="7">Paused by Admin</option>
                        <option value="8">Stopped by Admin</option>
                      </select>
                </div>
            </div>

            <!-- top area-before-table -->
            <div class="table-responsive-lg" style="overflow-x: auto;">
                <table class="table table-big table-hover table-middle">
                    <thead>
                        <tr class="table-row">
                            <th scope="col" class="white-space-pre text-center">Status</th>

                            <th scope="col" class="white-space-pre">Job Title</th>
                            <th scope="col" class="white-space-pre text-center">Not Rated</th>
                            {{-- <th scope="col" class="white-space-pre text-center">Edit</th> --}}
                            <th scope="col" class="white-space-pre text-center">Pause/Start</th>
                            <th scope="col" class="white-space-pre text-center">More</th>
                        </tr>
                    </thead>
                    <tbody id="jobs-list">
                        @foreach ($tasks as $task)
        
                        <tr class="table-row clickable">
                            <td scope="row" class="table-cell-status text-center">
                                @if ($task->status == 0)
                                <span class="badge rounded-pill text-bg-warning p-2">Pending Approval</span>
                                @elseif ($task->status == 1)
                                <span class="badge rounded-pill text-bg-success p-2">Active</span>
                                @elseif ($task->status == 2)
                                <span class="badge rounded-pill text-bg-danger p-2">Declined</span>
                                @elseif ($task->status == 3)
                                <span class="badge rounded-pill text-bg-secondary p-2">Paused</span>
                                @elseif ($task->status == 6)
                                <span class="badge rounded-pill text-bg-info p-2">Budget Exceeded</span>
                                @elseif ($task->status == 7)
                                <span class="badge rounded-pill text-bg-dark p-2">Admin Paused</span>
                                @elseif ($task->status == 8)
                                <span class="badge rounded-pill text-bg-danger p-2">Admin stopped</span>    
                                @endif
                            </td>

                            <td class="table-cell-name">
                                <a href="/advertiser/campaign/{{ $task->id }}">{{ $task->title }}</a>
                            </td>
                            <td class="table-cell-rated text-center"><a href="/advertiser/campaign/{{ $task->id }}">{{ $task->submittedProofs->whereIn('status', [0,4])->count() }}</a></td>
                            {{-- <td class="table-cell-settings p-0 text-center">
                                <a href="campaign-detail.html">
                                    <i class="fa fa-pencil-square-o side-icons" aria-hidden="true"></i>
                                </a>
                            </td> --}}
                            <td class="table-cell-settings p-0 text-center">
                                <a wire:click="pauseResume('{{ $task->id }}')">
                                    @if ($task->status == 1)
                                        <i class="fa fa-pause side-icons text-primary" aria-hidden="true"></i>
                                    @elseif($task->status == 3)
                                        <i class="fa fa-play side-icons text-primary" aria-hidden="true"></i>
                                    @endif
                                </a>
                            </td>

                            <td class="table-cell-settings p-0">
                                <div class="dropdown dropleft">
                                    <button type="button" class="btn btn-lg btn-link pl-1 pr-3 d-md-block w-100"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu with-icons">
                                        <a class="dropdown-item pw-emp-menu-item" href="/advertiser/campaign/{{ $task->id }}"><i
                                                class="fas fa-info-circle"></i> Job Details</a>
                                        {{-- <a class="dropdown-item pw-emp-menu-item" href="#"><i class="fas fa-copy"></i>
                                            Clone Job</a> --}}
                                        <a class="dropdown-item pw-emp-menu-item" href="/advertiser/campaign/{{ $task->id }}"><i class="fas fa-eye"></i>
                                            View Approved Proofs</a>
                                        <a class="dropdown-item pw-emp-menu-item" href="/advertiser/campaign/{{ $task->id }}"><i class="fas fa-eye"></i>
                                            Review pending Proofs</a>
                                        <a class="dropdown-item pw-emp-menu-item" href="/advertiser/campaign/{{ $task->id }}"><i class="fas fa-eye"></i>
                                            Review Rejected Proofs</a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- demo -->
        </div>
    </div>
    <!-- My campaigns end -->
</div>
