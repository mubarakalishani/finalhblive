<div class="container-fluid">

    <!-- all PTC ads start -->
    <div class="all-history-page">
        <!-- ***** All PTC ads start ***** -->

        <div class="main-profile mycampaigns-page>
            <div class="row align-items-end mb-4 pb-2">
                <div class="col-md-8">
                    <div class="section-title text-md-start">
                        <h4 class="title mb-4">Deposit</h4>
                    </div>
                </div>
                <!--end col-->
                <div class="tab-content" id="nav-tabContent">
                        <div class="row">
                            <div class="col col-lg-4 col-md-12 col-sm-12">
                                <div class="my-5">
                                    <span class="mb-1">select the payment method</span>
                                    <select class="form-select py-2" wire:model.live="selectedMethod">
                                        @foreach ($depositMethods as $depositMethod)
                                            <option value="{{ $depositMethod->name }}">{{ $depositMethod->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($selectedGateway->auto == 1)
                                        <div class="input-group mb-3 mt-3 py-2">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                            <input type="number" class="form-control" placeholder="amount in usd" wire:model.live.debounce.500ms="amount">
                                            @error('minamount') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <a href="{{ $paymentUrl }}" target="_blank" class="btn btn-primary text-center my-3">Add funds</a>
                                    @endif
                                </div>
                            </div>
                            @if($selectedGateway->auto == 0)
                                <div class="col col-lg-8 col-md-12 col-sm-12">
                                    <div class="my-5">
                                        {!! $selectedGateway->description !!}
                                    </div>
                                </div>
                            @endif    
                        </div>
                        <!--end row-->

                        {{-- make same table as the job history table --}}
                        <h6>Your Deposit Hisory:</h6>
                        <div class="table-responsive-lg" style="overflow-x: auto;">
                            <table class="table table-big table-hover table-middle">
                                <thead>
                                    <tr class="table-row">
                                        <th scope="col" class="white-space-pre text-center">Method</th>

                                        <th scope="col" class="white-space-pre">Amount</th>
                                        <th scope="col" class="white-space-pre text-center">Transaction ID</th>
                                        <th scope="col" class="white-space-pre text-center">Status</th>
                                        <th scope="col" class="white-space-pre text-center">Submitted</th>
                                        <th scope="col" class="white-space-pre text-center">Last Update</th>
                                    </tr>
                                </thead>
                                <tbody id="jobs-list">
                                    @foreach ($depositLogs as $depositLog)
                                        <tr class="table-row clickable">
                                            <td class="table-cell-name">
                                                {{ $depositLog->method }}
                                            </td>
                                            <td class="table-cell-name">
                                                {{ $depositLog->amount }}
                                            </td>
                                            <td class="table-cell-progress text-center">
                                                {{ $depositLog->internal_tx }}
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill py-2 px-2  @if($depositLog->status=='completed') text-bg-success @else text-bg-primary @endif">{{ $depositLog->status }}</span>
                                            </td>
                                            <td class="table-cell-rated text-center">{{ $depositLog->created_at->diffForHumans() }}</td>
                                            <td class="table-cell-rated text-center"><strong>{{ $depositLog->updated_at->diffForHumans() }}</strong></td>
                                        </tr>
                                    @endforeach   
                                </tbody>
                            </table>
                            <div class="d-flex">
                                {{ $depositLogs->links() }}
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
            <!--end row-->
        </div>
        <!-- All PTC ads end -->
    </div>
</div>
