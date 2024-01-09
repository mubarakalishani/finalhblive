<div class="container-fluid">

    <div class="all-history-page">
        <!-- ***** My campaigns Start ***** -->

        <div class="main-profile mycampaigns-page">
            <div class="col-lg-12">
                <div class="heading-section">
                    <h4><em>Withdrawal</em></h4>
                </div>

                <div class="row">
                    <div class="col col-lg-4">
                        <div class="mb-3">
                            <select class="form-select" wire:model.live="selectedGateway">
                                <option selected value="">Select payment method</option>
                                @foreach ($payoutGateways as $gateway)
                                    <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($selectedGateway > 0)
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">{{ $placeholder }}</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="{{ $placeholder }}" wire:model.lazy="wallet">
                            @error('wallet') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @endif
                        @if ($selectedGateway > 0)
                            <div class="input-group mb-3">
                            <span class="input-group-text">Amount: $</span>
                            <input type="number" class="form-control" wire:model.live="amount">
                            <span class="input-group-text">
                                <a class="text-primary mx-2" wire:click="updateAmount('50')">50%</a>|
                                <a class="text-primary mx-2" wire:click="updateAmount('100')">max</a>
                            </span>
                            @error('errAmount') <span class="text-danger">{{ $message }}</span> @enderror
                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif    
                        @if ($selectedGateway > 0 && $amount > 0)
                            <p>Processing Fee: ${{ $processingFee }}<br>You will receive: {{$amount - $processingFee}}</p>
                        @endif
                    </div>
                </div>
                
                @if($selectedGateway > 0 && $amount <= auth()->user()->balance && $amount > 0 && $wallet)
                    <button class="btn btn-primary text-center mb-5" wire:click="submit">Request now</button>
                @else
                    <button class="btn btn-primary text-center mb-5" disabled>Request now</button>
                @endif

                @if (session()->has('successMessage'))
                    <div class="bg-success p-2 text-white bg-opacity-50 text-light"> {{ session('successMessage') }}</div>
                @endif

                <!-- top area-before-table -->
                <div class="table-responsive-lg">
                    <h6>Withdrawal history</h6>
                    <table class="table table-big table-hover table-middle">
                        <thead>
                            <tr class="table-row">
                                <th scope="col" class="white-space-pre text-center">Status</th>

                                <th scope="col" class="white-space-pre text-center">Method</th>
                                <th scope="col" class="white-space-pre text-center">Amount</th>
                                <th scope="col" class="white-space-pre text-center">Address</th>
                                <th scope="col" class="white-space-pre text-center">Submitted</th>
                                <th scope="col" class="white-space-pre text-center">Last Update</th>
                            </tr>
                        </thead>
                        <tbody id="jobs-list">
                            @foreach ($withdrawalHistories as $withdrawalHistory)
                                <tr class="table-row clickable">
                                    <td scope="row" class="text-center">
                                        <span class="badge rounded-pill py-2 px-2 
                                            @if($withdrawalHistory->status==0) bg-warning 
                                            @elseif($withdrawalHistory->status==1) bg-success 
                                            @elseif($withdrawalHistory->status==2) bg-primary
                                            @elseif($withdrawalHistory->status==3) bg-danger
                                            @endif">
                                            @if($withdrawalHistory->status==0) Pending 
                                            @elseif($withdrawalHistory->status==1) Completed 
                                            @elseif($withdrawalHistory->status==2) Refunded 
                                            @elseif($withdrawalHistory->status==3) Cancelled
                                            @endif
                                        </span>
                                        {{-- @if($withdrawalHistory->status==2)
                                            <i class="far fa-question-circle text-primary"></i>
                                        @endif     --}}
                                    </td>

                                    <td class="table-cell-name text-center">
                                        {{ $withdrawalHistory->method }}
                                    </td>
                                    <td class="table-cell-progress text-center">
                                        <strong>${{ $withdrawalHistory->amount_after_fee }}</strong>
                                    </td>
                                    <td class="table-cell-progress text-center">
                                        {{ $withdrawalHistory->wallet }}
                                    </td>
                                    <td class="table-cell-rated text-center">{{ $withdrawalHistory->created_at->diffForHumans() }}</td>
                                    <td class="table-cell-rated text-center">{{ $withdrawalHistory->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach    
                        </tbody>
                    </table>
                </div>
                <div class="d-flex">
                    {{ $withdrawalHistories->links() }}
                </div>
                <div class="my-4">
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
        <!-- My campaigns end -->
    </div>
</div>