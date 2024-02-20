<div class="container-fluid">

    <!-- all PTC ads start -->
    <div class="all-history-page">
        <!-- ***** All PTC ads start ***** -->

        <div class="main-profile mycampaigns-page>
            <div class="row align-items-end mb-4 pb-2">
                <div class="col-md-8">
                    <div class="section-title text-md-start">
                        <h4 class="title mb-4">Transfer Main Balance to Advertising Balance</h4>
                    </div>
                </div>
                <!--end col-->
                <div class="tab-content" id="nav-tabContent">
                        <div class="row">
                            <div class="col col-lg-4 col-md-12 col-sm-12">
                                <div class="my-5">
                                    <div class="input-group mb-3 mt-3 py-2">
                                        <span class="input-group-text" id="basic-addon1">Amount $</span>
                                        <input type="number" class="form-control" placeholder="amount in usd" wire:model.live.debounce.500ms="amount" value="{{ $amount }}">
                                        @error('minamount') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    @if ($amount <= auth()->user()->balance && $amount > 0)
                                        <a class="btn btn-primary text-center my-3" wire:click="mainToDepositBalance">Transfer</a>
                                    @else   
                                        <a class="btn btn-secondary text-center my-3">Transfer</a> 
                                    @endif
                                </div>
                            </div>   
                        </div>
                        <!--end row-->
                </div>
            </div>
            <!--end row-->
        </div>
        <!-- All PTC ads end -->
    </div>
</div>

