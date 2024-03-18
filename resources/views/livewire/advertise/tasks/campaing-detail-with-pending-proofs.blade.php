<div class="container-fluid">
    <div class="all-history-page all-surveys-page">
      <div class="main-profile campaigndetails-page">
        <div class="col-lg-12">
          <div class="sub-header mb-6">
            <div class="sub-header__back mb-2">
              <a href="/advertiser/campaigns"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                <span>Back</span>
              </a>
            </div>
            <div class="heading-section">
              <h4>{{ $task->title }}</h4>
            </div>
          </div>
          <div class="results-bar mb-4">
            <div class="mb-0">
              <span class="d-flex justify-content-between">
                <span class="d-md-inline d-block mr-2 mb-2 p-2 @if ($task->status == 0)
                  bg-warning
                  @elseif ($task->status == 1)
                  bg-success
                  @elseif ($task->status == 2)
                  bg-danger
                  @elseif ($task->status == 3)
                  bg-secondary
                  @elseif ($task->status == 6)
                  bg-info
                  @elseif ($task->status == 7)
                  bg-dark
                  @elseif ($task->status == 8)
                  bg-danger
                  @endif
                  dot--declined text-white text-uppercase font-weight-bold text-center">@if ($task->status == 0)Pending
                  Approval
                  @elseif ($task->status == 1)
                  Active
                  @elseif ($task->status == 2)
                  Declined
                  @elseif ($task->status == 3)
                  Paused
                  @elseif ($task->status == 6)
                  Budget Exceeded
                  @elseif ($task->status == 7)
                  Admin Paused
                  @elseif ($task->status == 8)
                  Admin stopped
                  @endif
                </span>
                <span class="d-inline-block py-1 top-icons">
                  {{-- <span><a href="#"><i class="fas fa-edit"></i> Edit</a></span></span> --}}
              </span>
            </div>
            {{-- <div class="mb-0 text-right mt-3 mb-3 submit-dropdown-btns">
              <span class="white-space-pre "><a href="submitted-task.html"><i class="fas fa-clipboard-check mr-1"></i>
                  Submitted Proofs</a>
              </span>
            </div> --}}
            
            @if ($task->status == 2 || $task->status == 8)
              <span class="mt-3 bg-danger text-white font-weight-bold">
                {{ $task->remarks }}
              </span>
            @endif

            @if ($task->status == 7)
              <span class="mt-3 bg-primary text-white font-weight-bold">
                The job has been paused by the admin and is under investigation, it will be shortly either resumed or stopped.
              </span>
            @endif
            
            <div class="p-3 p-sm-6 bg-gray mb-6 detail-campaign-area">
              <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                  <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                      <p class="mb-0"><strong>Job ID</strong></p>
                    </div>
                    <div class="col-sm-7 col-md-8 details-text-style">
                      <p class="mb-0 font-weight-bold" id="jid">{{$task->id}}</p>
                    </div>
                  </div>
                  <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                      <p class="mb-0"><strong>Total Proof Submitted</strong></p>
                    </div>
                    <div class="col-7 col-md-8 details-text-style">
                      <p class="mb-0 font-weight-bold">
                        {{ $task->submittedProofs->count() }}
                      </p>
                    </div>
                  </div>
                  <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                      <p class="mb-0"><strong>Average Cost Per Submission</strong></p>
                    </div>
                    <div class="col-sm-7 col-md-8 details-text-style">
                      <p class="mb-0 font-weight-bold">{{ $task->targetedCountries->first()->amount_per_task }}</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-md-0">
                  
                  <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                      <p class="mb-0"><strong>Time to rate tasks</strong></p>
                    </div>
                    <div class="col-sm-7 col-md-8 details-text-style">
                      <p class="mb-0 font-weight-bold">{{ $task->rating_time }} days</p>
                    </div>
  
                    <div class="row mb-4 mb-sm-2">
                      <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                        <p class="mb-0"><strong>Spent So Far:</strong></p>
                      </div>
                      <div class="col-sm-7 col-md-8 details-text-style">
                        <p class="mb-0 font-weight-bold">${{ number_format($task->submittedProofs->where('status', 1)->sum('amount'), 3) }}
                        </p>
                      </div>
                    </div>
                    <div class="row mb-4 mb-sm-2">
                      <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                        <p class="mb-0"><strong>Pending Spend:</strong></p>
                      </div>
                      <div class="col-sm-7 col-md-8 details-text-style">
                        <p class="mb-0 font-weight-bold">${{ number_format($task->submittedProofs->where('status', 0)->sum('amount'), 3) }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                  {{-- <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-3 mb-sm-0 col-md-4 details-text-style ">
                      <p class="mb-0"><strong>Targeted Countries</strong></p>
                      <div class="mb-0 modify-btn">
                        <a href="#">
                          <i class="fa fa-pencil"></i> <span>Modify</span>
                        </a>
                        <div id="jte" class="ef text-danger ef--target-countries" style="width:300px;text-align:left;">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-7 col-md-8 details-text-style">
                      <p class="mb-0">
                        <span class="font-weight-medium">International - Worldwide</span>
                      </p>
                      <p class="mb-0">
                        <span class="text-gray-400 font-weight-medium">
                          Excluded: —
                        </span>
                      </p>
                    </div>
                  </div> --}}
                  <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                      <p class="mb-0"><strong>Category/sub Category:</strong></p>
                    </div>
                    <div class="col-sm-7 col-md-8 details-text-style">
                      <p class="mb-0 font-weight-bold">{{ $task->taskCategory->name }} / {{ $task->subCategory->name }}
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-md-0">
                  <div class="row mb-4 mb-sm-2">
                    <div class="col-sm-5 mb-1 mb-sm-0 col-md-4 details-text-style">
                      <p class="mb-0"><strong>Job Submitted</strong></p>
                    </div>
                    <div class="col-sm-7 col-md-8 details-text-style">
                      <p class="mb-0 font-weight-bold">{{ $task->created_at->diffForHumans() }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <h6>Tasks Requirements:</h6>
          <ol>
            @foreach($task->stepDetails as $step)
            <li>{{ $step->step_details }}</li>
            @endforeach
          </ol>
          <hr>
          <h6>Required Proofs:</h6>
          <ol>
            @foreach($task->requiredProofs as $proof)
            <li>{{ $proof->proof_text }}</li>
            @endforeach
          </ol>
        </div>
        {{-- quick edit --}}
        <div class="p-3 p-sm-6 bg-gray mb-6 detail-campaign-area">
          <div class="d-grid gap-2 mt-3 mb-3">
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button"
              aria-expanded="false" aria-controls="collapseExample">
              <i class="fa fa-pencil"></i> Quick Edit
            </a>
          </div>
          <div class="collapse" id="collapseExample" wire:ignore.self>
            <div class="card card-body">
              @if (session()->has('quickedit'))
                  <div class="alert alert-success m-2">
                      {{ session('quickedit') }}
                  </div>
                @endif
              <div class="mb-3">
                <label class="form-label">Max Campaign Budget in USD: (put 0 for unlimited)</label>
                <input type="number" class="form-control" value="{{ $task->max_budget }}" wire:model.lazy="maxBudget">
              </div>
  
              <div class="mb-3">
                <label class="form-label">Maximum Daily Spend in USD: (put 0 for unlimited)</label>
                <input type="number" value="{{ $task->daily_budget }}" wire:model.lazy="dailyBudget" class="form-control">
              </div>
  
              <div class="mb-3">
                <label class="form-label">Maximum Weekly Spend in USD: (put 0 for unlimited)</label>
                <input type="number" value="{{ $task->weekly_budget }}" wire:model.lazy="weeklyBudget" class="form-control">
              </div>
  
              <div class="mb-3">
                <label class="form-label">Max Hourly Spend in USD: (put 0 for unlimited)</label>
                <input type="number" value="{{ $task->hourly_budget }}" wire:model.lazy="hourlyBudget" class="form-control">
              </div>
  
              <div class="mb-3">
                <label class="form-label">Daily Maximum Submission : (put 0 for unlimited)</label>
                <input type="number" value="{{ $task->submission_per_day }}" wire:model.lazy="submissionPerDay" class="form-control">
              </div>
  
              <div class="mb-3">
                <label class="form-label">Hourly Maximum Submission : (put 0 for unlimited)</label>
                <input type="number" value="{{ $task->submission_per_hour }}" wire:model.lazy="submissionPerHour" class="form-control">
              </div>
  
              <div class="mb-3">
                <label class="form-label">Weekly Maximum Submission : (put 0 for unlimited)</label>
                <input type="number"  value="{{ $task->submission_per_week }}" wire:model.lazy="submissionPerWeek" class="form-control">
              </div>
  
            </div>
          </div>
        </div>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
        <div class="all-history-page">
          <div class=" mycampaigns-page">
            <div class="col-lg-12 pt-2">
              <div class="heading-section mt-3">
                  <h4>Pending Tasks</h4>
              </div>
              {{-- <div class="alert alert-with-icon pw-badge-danger fade show font-weight-medium" role="alert">
                  <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                  <a class="password-change-link text-danger">Jobs older than 6 months are no longer available.</a>
              </div> --}}
              @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
              @endif
                <!-- filter start -->
              <div class="results-bar d-flex align-items-center justify-content-between flex-wrap">
                  <button id="approveCurrentView" class="btn btn-success" wire:click="approveAllInCurrentView">Approve All in current view</button>
                  <div class="d-flex flex-wrap">
                    @if ($selectedMultiple == 'yes' )
                      <button class="btn btn-success" wire:click="approveAllSelected">Approve All Selected Tasks</button>
                    @endif
                    
                    {{-- <div class="list-filter dropdown" data-job-filter="sort">
                      <a class="dropdown-toggle btn btn-link" href="#" role="button" id="dropdownMenuCountery" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort by <span class="list-filter__sep">/</span>
                        <span class="list-filter__selection">
                          Newest
                        </span>
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item active job-search-form" data-filter="sort" data-filter-value="NEWEST">Newest</a>
                        <a class="dropdown-item job-search-form" data-filter="sort" data-filter-value="OLDEST">Date Submitted</a>
                        <a class="dropdown-item job-search-form" data-filter="sort" data-filter-value="COST">Date Approved</a>
                      </div>
                    </div> --}}
                  </div>
              </div>
              <!-- top area-before-table -->
              <div class="all-history-page mt-3">
                <div class="row align-items-end mb-4 pb-2">
                  <nav class="mt-0">
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active"><a href="?tab=pending">Pending</a></button>
                        <button class="nav-link"><a href="?tab=approved">Approved</a></button>
                        <button class="nav-link"><a href="?tab=rejected">Rejected</a></button>
                        <button class="nav-link"><a href="?tab=revision">Asked Revision</a></button>
                      </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <!-- Pendding task start -->
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                      <div class="pendding-task-area mt-2">
                        <div class="table-responsive">
                          <table class="table table-bordered">
                            <thead>
                                <th scope="col" class="table-dark table-taskdetail-area">  
                                Submitted Proofs:
                                </th>
                                <th scope="col" class="table-dark table-user-area">User info
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($proofs as $proof)
                              <tr>
                                <td>
                                  <div class="form-check">
                                    <input id="flexCheck{{ $proof->id }}" class="form-check-input" wire:model.live="batchSelectedProofs" type="checkbox" value="{{ $proof->id }}">
                                  </div>
                                  <strong>Text Proofs:</strong>
                                  <ol>
                                    @foreach ($proof->textProofs as $textProof)
                                      <li><p>{{ $textProof->proof_text }}</p></li>
                                    @endforeach
                                  </ol>
                                  <strong>Image/Screenshots:</strong>
                                  <ol>
                                    @foreach ($proof->imageProofs as $imageProof)
                                      <li><img style="max-width: 320px;" src="{{ asset('storage/proofs/'.$imageProof->url)}}" alt="proof img" width="320px"></li>
                                    @endforeach
                                  </ol>
                                </td>
                                <td>
                                    <button id="proof{{$proof->id}}" class="btn btn-success" wire:click="approve('{{ $proof->id }}')">Approve</button> 
                                    <button id="proof{{$proof->id}}" class="btn btn-danger my-2" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="rejection" data-bs-proof-id="{{ $proof->id }}">Reject</button> <br>
                                    <button id="proof{{$proof->id}}" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="revision" data-bs-proof-id="{{ $proof->id }}">Ask For Revision</button> <br>
                                    <strong>user :</strong>
                                    <p>{{ $proof->worker->username }}</p>
                                    <strong>Date Submitted :</strong>
                                    <p>{{ $proof->created_at }}</p>
                                    
                                    <strong>Country :</strong>
                                    <p>{{ $proof->worker->country }}</p>
                                    <strong>Reward :</strong>
                                    <p>${{ $proof->amount }}</p>
                                </td>
                              </tr>
                              @endforeach
                              <!-- Add more rows as needed -->
                            </tbody>
                          </table>
                        </div>
                        <div class="d-flex">
                            {{ $proofs->links() }}
                        </div>
                        <div class="d-flex flex-wrap mt-2">
                            <select wire:model.live="perPage">
                                  <option value="5">5</option>
                                  <option value="10" selected>10</option>
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
  
  
  
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Reason: </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="POST" wire:submit.prevent="submitEmployerComment" id="commentemployer">
                    <input type="hidden" class="form-control" id="rejectOrRevision" wire:model.lazy="rejectOrRevision" autofocus>
                    <input type="hidden" class="form-control proof-id" wire:model.lazy="proofId" autofocus>
                  
                  <div class="mb-3">
                      <label for="reasonSelected">Select Rejection/Revision Reason: </label>
                    <select name="reasonSelected" class="form-select" aria-label="Default select example" wire:model.live='reasonSelected' required>
                      <option value="">Select the reason: </option>
                        @foreach ($availableRejectionReasons as $reason)
                            <option value="{{ $reason->reason }}">{{ $reason->reason }}</option>
                        @endforeach
                    </select>
                    @error('reasonSelected') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                  <div class="mb-3">
                    <label for="reasonExplained" class="col-form-label">Explain Rejection/Revision Reason:</label>
                    <textarea name="reasonExplained" class="form-control" id="message-text" wire:model.lazy="reasonExplained"></textarea>
                    @error('reasonExplained') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="commentemployer" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
              </div>
            </div>
          </div>
        </div>
  
  
  
  
      </div>
    </div>
  </div>
