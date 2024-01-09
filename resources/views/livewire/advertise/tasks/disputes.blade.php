<div class="container-fluid">

    <div class="all-history-page">
        <!-- ***** My campaigns Start ***** -->

        <div class=" mycampaigns-page">
            <div class="col-lg-12 pt-2">
                <div class="heading-section mt-3">
                    <h4>Disputes</h4>
                </div>

                <!-- filter start -->

                <div class="results-bar d-flex align-items-center justify-content-between flex-wrap">
                    <p class="mb-0 mr-4 results-bar__total"><span> </span> </p>
                    <div class="d-flex flex-wrap">
                        <div class="list-filter dropdown mr-4">
                            <select class="" wire:model.live='search'>
                                <option value="">Filter by tasks / All</option>
                                @foreach ($employerTasks as $employerTask)
                                    <option value="{{ $employerTask->title }}">{{ $employerTask->id }}-{{ $employerTask->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="tab-content" id="nav-tabContent">
                    <!-- Pendding task start -->
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="pendding-task-area mt-2">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>

                                            <th scope="col" class="table-primary table-taskdetail-area">
                                                Details
                                            </th>
                                            <th scope="col" class="table-primary table-user-area">User info
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disputeProofs as $proof)
                                            <tr>
                                            <td>
                                                <p><strong>Campaign ID :</strong>
                                                    {{ $proof->task->id }}</p>
                                                <span class="tiles-of-table"><strong>Title :</strong></span>
                                                <p>{{ $proof->task->title }}</p>
                                                <strong>Instructions :</strong>
                                                <ol>
                                                    @foreach ($proof->task->stepDetails as $step)
                                                        <li>{{ $step->step_details }}</li>  
                                                    @endforeach
                                                </ol>
                                                <strong>Proofs Required :</strong>
                                                <ol>
                                                    @foreach ($proof->task->requiredProofs as $requiredProof)
                                                        <li>{{ $requiredProof->proof_text }}</li>  
                                                    @endforeach
                                                </ol>
                                                <strong>User's Submitted Proofs :</strong>
                                                <ol>
                                                    @foreach ($proof->textProofs as $textProof)
                                                        <li>{{ $textProof->proof_text }}</li>  
                                                    @endforeach
                                                    @foreach ($proof->imageProofs as $imageProof)
                                                    <li>{{ $imageProof->url }}</li>  
                                                @endforeach
                                                </ol>
                                                <strong class="text-danger">Your Rejection Reason :</strong>
                                                <p>{{ $proof->revisionApprovalReason->selected_reason  }} <br>{{ $proof->revisionApprovalReason->employer_comment  }}</p>
                                                @if($proof->dispute)
                                                    <strong class="text-danger">User's Dispute Description :</strong>
                                                    <p>{{ $proof->dispute->description  }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-success" wire:click="approve('{{ $proof->id }}')">Approve</button> <button
                                                    class="btn btn-danger my-2" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-proof-id="{{ $proof->id }}">Reject</button> <br>
                                                <strong>username :</strong>
                                                <p>{{ $proof->worker->username }}</p>
                                                <strong>Date Submitted :</strong>
                                                <p>{{ $proof->created_at }}</p>
                                                <strong>Date Dispute Filed :</strong>
                                                <p>{{ $proof->updated_at }}</p>

                                                <strong>Country :</strong>
                                                <p>{{ $proof->worker->country }}</p>
                                            </td>
                                            </tr>
                                        @endforeach    
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex">
                                {{ $disputeProofs->links() }}
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
            <!--end row-->
        </div>
        <!-- demo -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Reason: </h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form method="POST" wire:submit.prevent="submitEmployerComment" id="commentemployer">
                      <input type="hidden" class="form-control proof-id" wire:model.lazy="proofId" autofocus>
                    <div class="mb-3">
                      <label for="reasonExplained" class="col-form-label">Explain Your Rejection Reason:</label>
                      <textarea name="reasonExplained" class="form-control" id="message-text" wire:model.live="reasonExplained"></textarea>
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