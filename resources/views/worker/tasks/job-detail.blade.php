@extends('layouts.afterlogin')
@section('content')
                <div class="container-fluid">

                    <!-- user dashboard start -->
                    <div class="all-history-page jobs-page">
                        <!-- ***** My jobs Start ***** -->

                        <div class="main-profile jobs-page">
                          <div class="col-lg-12">
                            <!-- micro task start -->
                            <div class="sub-header__back mb-2">
                              <a href="/jobs"><i class="fa fa-arrow-left" aria-hidden="true"></i>
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
                
                            
                              <form action="{{ route('worker.submit_task', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="job-info-list mb-8 mt-8 col-12">
                                  <span class="job-info-list-heading"><span class="symbol--primary">?</span>Submit your proofs below
                                  </span>
                                  <div class=" list-da--style">
                                    <ol>
                                      @foreach($task->requiredProofs as $proof)
                                        @if ( $proof->proof_type == 1)
                                          <li class="py-1">{{ $proof->proof_text }}</li>
                                            <textarea name="text_proofs[{{ $proof->proof_no }}]" class="form-control"required></textarea>
                                        @else
                                        <li class="py-1">{{ $proof->proof_text }}</li>
                                          <div class="form-group">
                                            <input type="file" name="image_proofs[{{ $proof->proof_no }}]" class="form-control" required>
                                          </div>
                                        @endif  
                                      @endforeach  
                                    </ol>
                                  </div>
                                  <div class="job-info-submit-btn px-4 py-4">
                                    <span class="d-flex justify-content-between">
                                      <span class="d-block"><button type="submit" class="btn btn-primary py-2"> SUBMIT TASK</button></span>
                                    </span>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
@endsection