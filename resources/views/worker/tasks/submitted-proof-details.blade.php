@extends('layouts.afterlogin')
@section('content')
                <div class="container-fluid">
                    {{-- <div class="mt-3 mb-5">
                        <h3>Welcome Username</h3>
                    </div> --}}

                    <!-- user dashboard start -->
                    <div class="all-history-page jobs-page">
                        <!-- ***** My jobs Start ***** -->

                        <div class="main-profile jobs-page">
                          <div class="col-lg-12">
                            <!-- micro task start -->
                            <div class="sub-header__back mb-2">
                              <a href="/history/jobs"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                              <span>Back</span>
                              </a>
                            </div>
                             <div class="job-details-area mb-2">
                              <div class="row">
                                <div class="col">
                                  <div class="job-header py-3">
                                    <div class="job-header__info">
                                      <h1 class="headline">{{ $task->title }}</h1>
                                    </div>
                                  </div>
                                </div>
                                <div class="col">
                                  <div class="job-detail-price-area py-2">
                                    <div class="job-header__price">
                                      <small class="dollar-price">$</small>
                                      <span class="text-xl fw-bold d-inline-block">{{ $task->targetedCountries->where('country', auth()->user()->country)->first()->amount_per_task }}</span>
                                    </div>
                                    <p>Job Price</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="results-bar d-flex align-items-center justify-content-center justify-content-md-between flex-wrap">
                              {{-- <p class="mb-0 mr-4 results-bar__total pb-2 pb-md-0"><span id="">1</span> results</p> --}}
                              <div class="d-flex flex-wrap mb-2">
                                <a href="#" class="after-title-buttons px-2 py-1">Report Job</a><span class="after-title-btn-span"></span>
                                {{-- <a href="#" class="after-title-buttons px-2 py-1">Block Employer </a><span class="after-title-btn-span">|</span>
                                <a href="#" class="after-title-buttons px-2 py-1">Follow Employer</a> --}}
                              </div>
                            </div>
                            <div class="p-3 p-sm-6 bg-gray mb-6 detail-campaign-area ">
                              <div class="job-bar__header d-flex align-items-top justify-content-between mb-3">
                                <h3 class="job-bar__heading fw-medium mb-0 h4">
                                  <span class="js-job-item-name">{{ $task->title }}</span>
                                </h3>
                              </div>
                                  <div class="row">
                                    <div class="col">
                                      <div class="row col">
                                        <div class="col mb-1 mb-sm-0 details-text-style">
                                          <span class="mb-0 jobs-card-ma-headings">Employer Stats:</span>
                                      </div>
                                      <div class="col ">
                                        <p class="mb-0 jobs-card-after-head" id="jid">{{ $task->employer->uername }}</p>
                                      </div>
                                    </div>
                                    <div class="row col">
                                      <div class="col mb-1 mb-sm-0 ">
                                        <span class="mb-0 jobs-card-ma-headings">Time to rate</span>
                                      </div>
                                      <div class="col ">
                                        <p class="mb-0 jobs-card-after-head">{{ $task->rating_time }} days</p>
                                      </div>
                                    </div>
                                    <div class="row  col">
                                      <div class="col mb-1 mb-sm-0 ">
                                        <span class="mb-0 jobs-card-ma-headings">Employer</span>
                                      </div>
                                      <div class="col ">
                                        <p class="mb-0 jobs-card-after-head"><a href="#"> <i class="fas fa-external-link-alt"> {{$task->employer->username}} </i></a></p>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col">
                                    <div class="row col">
                                      <div class="col mb-1 mb-sm-0 ">
                                        <span class="mb-0 jobs-card-ma-headings">Category / SubCategory:</span>
                                      </div>
                                      <div class="col ">
                                        <p class="mb-0 jobs-card-after-head">{{$task->taskCategory->name }} / {{ $task->subCategory->name }}</p>
                                      </div>
                                    </div>
                                    <div class="row col">
                                      <div class="col mb-1 mb-sm-0 ">
                                        <span class="mb-0 jobs-card-ma-headings">Member Since:</span>
                                      </div>
                                      <div class="col ">
                                        <p class="mb-0 jobs-card-after-head">{{ $task->employer->created_at->diffForHumans() }}</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <hr>
                            </div>
                            <!-- micro task end -->
                            <!-- required from user -->
                            <div class="row">
                              <div class="job-info-list mb-8 mt-8 col-12">
                                <span class="job-info-list-heading"><span class="symbol--primary mb-3">?</span>What is expected from workers?
                                </span>
                                <div class=" list-da--style">
                                  <ol>
                                    @foreach($task->stepDetails as $step)
                                        <li>{{ $step->step_details }}</li>
                                    @endforeach
                                  </ol>
                                </div>
                                {{-- <div class="px-3 list-da--style ">Notes:<br>
                                    <br>
                                    Make sure to watch the correct video before submitting proof.<br>
                                    The Correct video title hint: Be******* *ate***** In Tr****** R*in *or*** <br>
                                    <br>
                                    Wrong video won't be paid and rate not satisfied<br>
                                    <br>
                                    Submitting spam proof will lead to ban<br>
                                    <br>
                                    After complete human verification, you Will be redirected to the video. <br>
                                    <br>
                                    Don't submit proof if you still didn't get the correct video
                                </div> --}}
                              </div>

                              <h6>Your Submitted Proof:</h6>
                                  <strong>Text Proofs:</strong>
                                  <ol>
                                    @foreach ($proof->textProofs as $textProof)
                                      <li><p>{{ $textProof->proof_text }}</p></li>
                                    @endforeach
                                  </ol>
                                  <strong>Image/Screenshots:</strong>
                                  <ol>
                                    @foreach ($proof->imageProofs as $imageProof)
                                      <li>{{ $imageProof->url }}</li>
                                    @endforeach
                                  </ol>
                                  <strong>Date Submitted : {{ $proof->created_at }}</strong>
                                  <strong>Status :  <span class="badge text-bg-primary py-2 px-2 rounded-pill
                                    @if($proof->status == 0)
                                    bg-warning
                                    @elseif($proof->status == 1)
                                        bg-success
                                    @elseif($proof->status == 2)
                                        bg-danger
                                    @elseif($proof->status == 3)
                                        bg-info
                                    @elseif($proof->status == 4)
                                        bg-warning
                                    @elseif($proof->status == 5)
                                        bg-primary
                                      @elseif($proof->status == 6)
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
                                        Dispute Rejected By  employer
                                    @endif
                                </span>

                                  </strong>
                                 @if ($proof->revisionApprovalReason)
                                 <div>
                                    <h6 class="mt-4">Employer Comment:</h6>
                                    <p class="ml-4"><b>Selected Reason:</b> {{ $proof->revisionApprovalReason->selected_reason }}</p>
                                    <p class="ml-4"><b>Employer Comment:</b> {{ $proof->revisionApprovalReason->employer_comment }}</p>
                                 </div> 
                                 @endif

                                 @if ($proof->status==2)
                                 <p>
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                      File A Dispute
                                    </button>
                                  </p>
                                  <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                        <form action="{{ route('worker.file_dispute', ['taskId' => $task->id])}}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label>Explain the Dispute Details Here:</label>
                                                <textarea name="description" class="form-control" required></textarea>
                                            </div>
                                            <input type="hidden" name="proofId" value="{{ $proof->id }}">
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>    
                                        </form>
                                    </div>
                                  </div>
                                 @endif


                                 @if ($proof->status == 5)
                                    <h6 class="mt-3">Your Dispute Description:</h6>
                                    <p>{{ $proof->dispute->description }}</p>
                                 @endif
                                 
                                     @if ($proof->status == 3)
                                            <h1>Resubmit Proof:</h1> 
                                            <form action="{{ route('worker.submit_revised_task', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="job-info-list mb-8 mt-8 col-12">
                                                  <span class="job-info-list-heading"><span class="symbol--primary">?</span>Submit your proofs below
                                                  </span>
                                                  <div class=" list-da--style">
                                                    <ol>
                                                      @foreach($task->requiredProofs as $requiredProof)
                                                        @if ( $requiredProof->proof_type == 1)
                                                          <li class="py-1">{{ $requiredProof->proof_text }}</li>
                                                            <textarea name="text_proofs[{{ $requiredProof->proof_no }}]" class="form-control"required></textarea>
                                                        @else
                                                        <li class="py-1">{{ $requiredProof->proof_text }}</li>
                                                          <div class="form-group">
                                                            <input type="file" name="image_proofs[{{ $requiredProof->proof_no }}]" class="form-control" required>
                                                          </div>
                                                        @endif  
                                                      @endforeach  
                                                    </ol>
                                                    <input type="hidden" name="proof_id" value="{{ $proof->id }}">
                                                  </div>
                                                  <div class="job-info-submit-btn px-4 py-4">
                                                    <span class="d-flex justify-content-between">
                                                      <span class="d-block"><button type="submit" class="btn btn-primary py-2"> SUBMIT TASK</button></span>
                                                    </span>
                                                  </div>
                                                </div>
                                            </form>
                                        @endif
                                
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
@endsection