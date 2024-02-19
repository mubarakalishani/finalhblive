
<div class="container-fluid">


      <div class="all-history-page jobs-page" style="padding-top: 10px; padding-bottom: 5px;">
        <div class="col-lg-12">
            <div class="jobs-tabs position-relative">
                <ul class="nav nav-tabs col-8 col-md-12">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Micro Jobs</a>
                    </li>
                </ul>
            </div>
            <nav class="navbar px-0 navbar-expand-lg">
                <div class=" navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Category
                            </a>
                            <ul class="dropdown-menu">
                              @foreach ($categories as $category)
                                <li><a class="dropdown-item" wire:click="selectCategory('{{ $category->id }}')">{{ $category->name }}</a></li>
                              @endforeach
                            </ul>
                        </li>

                        @if ($subCategories)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Sub Category
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($subCategories as $subCategory)
                                    <li class="px-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="selectedSubcategories" value="{{ $subCategory->id }}" id="subCategory{{ $subCategory->id }}" wire:change="loadSelectedSubCategoriesTasks">
                                            <label class="form-check-label" for="subCategory{{ $subCategory->id }}">
                                                {{ $subCategory->name }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Job Price
                            </a>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#">$0.01 > $0.1</a></li>
                              <li><a class="dropdown-item" href="#">$0.1 > $0.2</a></li>
                              <li><a class="dropdown-item" href="#">$0.2 > $0.5</a></li>
                            </ul>
                        </li> --}}

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Job filters
                            </a>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#" wire:click="sort('reward' , 'desc')">Reward - High to low</a></li>
                              <li><a class="dropdown-item" href="#" wire:click="sort('reward' , 'asc')">Reward - Low to High</a></li>
                              <li><a class="dropdown-item" href="#" wire:click="sort('id' , 'desc')">Jobs - Newest to oldest</a></li>
                              <li><a class="dropdown-item" href="#" wire:click="sort('id' , 'asc')">Jobs - Newest to oldest</a></li>
                              <li><a class="dropdown-item" href="#" >Jobs - High Success Rate to lowest</a></li>
                              <li><a class="dropdown-item" href="#">Jobs - Lowest Success Rate to Highest</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="results-bar d-flex align-items-center justify-content-center justify-content-md-between flex-wrap">
                    <div class="d-flex flex-wrap mb-2">
                        <div class="list-filter d-flex align-items-center col-12 col-md-auto p-0" data-job-filter="search">
                            <button class="btn btn-icon text-danger clear-search" style="display:none"><i
                                class="fas fa-times text-sm"></i></button>
                            <input type="text" wire:model.live="search" class="form-control" placeholder="Search job here" value="">
                        </div>
                        <div class="list-filter dropdown col-12 col-md-auto p-0 text-center" data-job-filter="sort">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sort by / Most Recent
                                </a>
                                <ul class="dropdown-menu">
                                  <li><a class="dropdown-item" href="#" wire:click="sort('reward' , 'desc')">Reward - High to low</a></li>
                                  <li><a class="dropdown-item" href="#" wire:click="sort('reward' , 'asc')">Reward - Low to High</a></li>
                                  <li><a class="dropdown-item" href="#" wire:click="sort('id' , 'desc')">Jobs - Newest to oldest</a></li>
                                  <li><a class="dropdown-item" href="#" wire:click="sort('id' , 'asc')">Jobs - Oldest to newest</a></li>
                                  <li><a class="dropdown-item" href="#">Jobs - High Success Rate to lowest</a></li>
                                  <li><a class="dropdown-item" href="#">Jobs - Lowest Success Rate to Highest</a></li>
                                </ul>
                            </li>
                        </div>
                    </div>
            </div>
            <div class="row">
                @foreach ($availableTasks as $task)
                @if ($task->hourly_submit_exceed == 0 && $task->daily_submit_exceed ==0 && $task->weekly_submit_exceed == 0 && $task->hourly_budget_exceed ==0 && $task->daily_budget_exceed == 0 && $task->weekly_budget_exceed == 0)
                  <div class="col-lg-6 col-md-12 col-sm-12">
                  <div class="p-3 p-sm-6 bg-gray mb-6 detail-campaign-area clickable-div" onclick="navigateToPage('/jobs/{{ $task->id }}')">
                    <div class="job-bar__header d-flex align-items-top justify-content-between mb-3">
                      <h3 class="job-bar__heading fw-medium mb-0 h4">
                        <span class="js-job-item-name">{{ $task->title }}</span>
                      </h3>
                      <div class="job-bar__actions flex-shrink-0">
                        <a href="#"><i class="fas fa-external-link-alt"></i></a>
                      </div>
                    </div>
                    <div class="row">
                    <div class="col">
                      <div class="row">
                        <div class="col mb-1 mb-sm-0 details-text-heading">
                          <p class="mb-0 py-1">Reward</p>
                        </div>
                        <div class="col details-text-style">
                          <p class="mb-0 py-1" id="jid">${{ $task->targetedCountries->first()->amount_per_task }}</p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col mb-1 mb-sm-0 details-text-heading">
                          <p class="mb-0 py-1">Approved/Rejected:</p>
                        </div>
                        <div class="col details-text-style">
                          <p class="mb-0 py-1">{{ $task->submittedProofs->where('status', 1)->count() }} / {{ $task->submittedProofs->where('status', 2)->count()}}</p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col mb-1 mb-sm-0 details-text-heading">
                          <p class="mb-0 py-1">Job Started</p>
                        </div>
                        <div class="col details-text-style">
                          <p class="mb-0 py-1">{{ $task->created_at->diffForHumans() }}</p>
                        </div>
                      </div>
                    </div>
        
                    <div class="col">
                        <div class="row">
                            <div class="col mb-1 mb-sm-0 details-text-heading">
                                <p class="mb-0 py-1">Time to rate:</p>
                            </div>
                            <div class="col details-text-style">
                                <p class="mb-0 py-1">{{ $task->rating_time }} Days</p>
                            </div>
                        </div>
        
                        <div class="row">
                            <div class="col mb-1 mb-sm-0 details-text-heading">
                                <p class="mb-0 py-1">Category:</p>
                            </div>
                            <div class="col details-text-style">
                                <p class="mb-0 py-1">{{ $task->taskCategory->name }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-1 mb-sm-0 details-text-heading">
                                <p class="mb-0 py-1">Sub-Category:</p>
                            </div>
                            <div class="col details-text-style">
                                <p class="mb-0 py-1">{{ $task->subCategory->name }}</p>
                            </div>
                        </div>
                    </div>
        
                    
        
                    </div>
                    <hr>
                  </div>
                  </div>
                @endif  
                @endforeach
            </div>
            <div x-intersect="$wire.loadMore()" style="visibility:hidden">Load More</div>
        </div>
      </div>
</div>



