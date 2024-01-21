<div class="container-fluid">
    <div class="all-history-page all-surveys-page">
        <!-- Profle billing settings start -->
        <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                {{-- <a class="nav-link  ms-0" href="profile-settings.html">Profile</a> --}}
                <a class="nav-link active" href="/profile/payout-methods">Payout Methods</a>
                <a class="nav-link" href="/profile/security">Security</a>
            </nav>
            <hr class="mt-0 mb-4">
            {{-- <div class="row">
                      <div class="col-lg-4 mb-4">
                          <!-- Billing card 1-->
                          <div class="card h-100 border-start-lg border-start-primary">
                              <div class="card-body">
                                  <div class="small text-muted">Current monthly bill</div>
                                  <div class="h3">$20.00</div>
                                  <a class="text-arrow-icon small" href="#!">
                                      Switch to yearly billing
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                  </a>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-4 mb-4">
                          <!-- Billing card 2-->
                          <div class="card h-100 border-start-lg border-start-secondary">
                              <div class="card-body">
                                  <div class="small text-muted">Next payment due</div>
                                  <div class="h3">July 15</div>
                                  <a class="text-arrow-icon small text-secondary" href="#!">
                                      View payment history
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                  </a>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-4 mb-4">
                          <!-- Billing card 3-->
                          <div class="card h-100 border-start-lg border-start-success">
                              <div class="card-body">
                                  <div class="small text-muted">Current plan</div>
                                  <div class="h3 d-flex align-items-center">Freelancer</div>
                                  <a class="text-arrow-icon small text-success" href="#!">
                                      Upgrade plan
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                  </a>
                              </div>
                          </div>
                      </div>
            </div> --}}



            <div class="card card-header-actions mb-4">
                <div class="card-header">
                    Add Payout Method
                </div>
                <div class="card-body px-0">
                        @if (session()->has('successMessage'))
                            <div class="bg-success p-2 text-white bg-opacity-50 text-light"> {{ session('successMessage') }}</div>
                        @endif
                        <div class="container">
                              <div class="mb-3">
                                  <label class="col-form-label">Method:</label>
                                  <select class="form-select" wire:model.live="selectedGateway">
                                      <option value="0">select</option>
                                          @foreach ($methods as $method)
                                              <option value="{{$method->id}}">{{ $method->name }}</option>
                                          @endforeach
                                  </select>
                              </div>
                              @if ($selectedGateway > 0)
                                  <div class="mb-3">
                                    <label for="message-text" class="col-form-label">{{$placeholder}}:</label>
                                    <input type="text" class="form-control" wire:model.lazy="wallet">
                                    @error('wallet') <span class="text-danger">{{ $message }}</span> @enderror
                                  </div>
                                  <button class="btn btn-primary p-x-4" wire:click="submit">Submit</button>
                              @endif
                        </div>
                    </div>
            </div>


            <!-- Payment methods card-->
            {{-- <div class="card card-header-actions mb-4">
                <div class="card-header">
                    Payout Methods
                </div>
                <div class="card-body px-0">
                    @if (session()->has('updateWalletMessage'))
                            <div class="bg-success p-2 text-white bg-opacity-50 text-light"> {{ session('updateWalletMessage') }}</div>
                    @endif
                    @foreach ($wallets as $wallet)
                        <div class="d-flex align-items-center justify-content-between px-4">
                            <div class="d-flex align-items-center">
                                 <img src="https://lolsurveys.com/assets/images/gateways/payeer.png" alt="">
                                <div class="ms-4">
                                    @if ($editId == $wallet->id)
                                        <div><input class="form-control" type="text" value="{{ $wallet->address }}" wire:model.lazy="wallet"></div>
                                        @if ($wallet->comment != null)
                                            <div class="text-xs text-muted">comment/memo: 
                                                <input type="text" value="{{ $wallet->comment }}" wire:model.lazy="comment">
                                            </div>
                                        @endif
                                    @else
                                        <div class="small">{{ $wallet->address }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="ms-4 small">
                                 <div class="badge bg-light text-dark me-3">Default</div> 
                                <a class="btn btn-outline-primary" wire:click="editRecord('{{ $wallet->id }}')">Edit</a>
                                <a class="btn btn-danger text-light" wire:click="deleteRecord('{{ $wallet->id }}')">Delete</a>
                                
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div> --}}

            <!------ the table starts-->
            <div class="card mb-4">
                <div class="card-header">Added Payout Methods</div>
                    <div class="card-body p-0">
                        @if (session()->has('updateWalletMessage'))
                            <div class="bg-success p-2 text-white bg-opacity-50 text-light"> {{ session('updateWalletMessage') }}</div>
                        @endif
                        <!-- Billing history table-->
                        <div class="table-responsive table-billing-history">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-gray-200" scope="col">Payout Method</th>
                                        <th class="border-gray-200" scope="col">Account Details</th>
                                        <th class="border-gray-200" scope="col">Delete/Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($wallets as $wallet)
                                    <tr>
                                        <td>{{ $wallet->gateway->name }}</td>
                                        <td>@if ($editId == $wallet->id)
                                            <div><input class="form-control" type="text" value="{{ $wallet->address }}" wire:model.lazy="wallet"></div>
                                        @else
                                            {{ $wallet->address }}
                                        @endif</td>

                                        <td>
                                            <a class="btn btn-outline-primary" wire:click="editRecord('{{ $wallet->id }}')">Edit</a>
                                            <a class="btn btn-danger text-light" wire:click="deleteRecord('{{ $wallet->id }}')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    
</div>
