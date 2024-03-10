<div class="container">
	<Style>
        .all-stats-details h2 {
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            text-decoration: underline;
            font-weight: 700;
        }
        .all-stats-details .card-box {
            position: relative;
            color: #fff;
            padding: 20px 10px 40px;
            margin: 20px 0px;
        }
        .all-stats-details .card-box:hover {
            text-decoration: none;
            color: #f1f1f1;
        }
        .all-stats-details .card-box:hover .icon i {
            font-size: 100px;
            transition: 1s;
            -webkit-transition: 1s;
        }
        .all-stats-details .card-box .inner {
            padding: 5px 10px 0 10px;
        }
        .all-stats-details .card-box h3 {
            font-size: 27px;
            font-weight: bold;
            margin: 0 0 8px 0;
            white-space: nowrap;
            padding: 0;
            text-align: left;
            color: white;
        }
        .all-stats-details .card-box p {
            font-size: 12px;
            color: white;
        }
        /*.all-stats-details .card-box .icon {
            position: absolute;
            top: auto;
            bottom: 5px;
            right: 5px;
            z-index: 0;
            font-size: 72px;
            color: rgba(0, 0, 0, 0.15);
        }*/
        .all-stats-details .card-box .card-box-footer {
            position: absolute;
            left: 0px;
            bottom: 0px;
            text-align: center;
            padding: 3px 0;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(0, 0, 0, 0.1);
            width: 100%;
            text-decoration: none;
        }
        .all-stats-details .card-box:hover .card-box-footer {
            background: rgba(0, 0, 0, 0.3);
        }
        .all-stats-details .bg-blue {
            background-color: #00c0ef !important;
        }
        .all-stats-details .bg-green {
            background-color: #00a65a !important;
        }
        .all-stats-details .bg-orange {
            background-color: #f39c12 !important;
        }
        .all-stats-details .bg-red {
            background-color: #d9534f !important;
        }
    </style>
    <div class="all-stats-details">
        <div class="row">
                <h2>Deposits</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> ${{ $deposits->sum('amount')}} </h4>
                            <p> Total Deposit </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> ${{ $deposits->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount') }} </h4>
                            <p> This Month </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $deposits->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount') }} </h4>
                            <p> Last Month </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> $723 </h4>
                            <p> Available Balance </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
        </div>
    </div>
</div>
<!----  Deposit area End ----->

<!----  Payments area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>Payments</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> ${{ $withdrawals->where('status', 1)->sum('amount_after_fee')}} </h4>
                            <p> Total Paid </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> ${{ $withdrawals->where('status', 0)->sum('amount_after_fee') }} </h4>
                            <p> Total Pending </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $withdrawals->where('status', 1)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount_after_fee') }} </h4>
                            <p> This Month paid</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> ${{ $withdrawals->where('status', 1)->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount_after_fee') }} </h4>
                            <p> Last Month paid</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
<!----  Payments area End ----->

<!---- Users Earning area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>Users Earning</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> ${{ $offers->sum('payout') }} </h4>
                            <p> Offerwalls earning </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> ${{ $shortlinks->sum('reward') }} </h4>
                            <p> Shortlinks earnings </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $ptcEarnings->sum('reward') }} </h4>
                            <p> PTC earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> ${{ $tasksEarning->sum('amount') }} </h4>
                            <p> Taks earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
<!----  Users Earning End ----->

<!---- Tasks area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>Tasks</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> ${{ $tasksEarning->sum('amount') }} </h4>
                            <p> Total Earned </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> {{ $pendingTasks }} </h4>
                            <p> pending Approval </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $tasksEarning->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount') }}  </h4>
                            <p> This Month earning</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> ${{ $tasksEarning->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount') }} </h4>
                            <p> Last Month earning</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
<!---- Tasks end ----->

<!---- Offers area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>Offers</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> {{$offers->sum('payout')}} </h4>
                            <p> Total offer completed </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> {{ $offers->whereDate('created_at', Carbon\Carbon::today())->sum('payout') }} </h4>
                            <p> Today offerds complete </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $offers->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('payout') }} </h4>
                            <p> This Month completed</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> ${{ $offers->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('payout') }} </h4>
                            <p> Last Month completed</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
<!---- Offers end ----->

<!---- Shortlinks area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>Shortlinks</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> {{ $shortlinks->sum('reward') }} </h4>
                            <p> Total earnings </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> {{ $shortlinks->whereDate('created_at', Carbon\Carbon::today())->sum('reward') }} </h4>
                            <p> Today earnings </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> {{ $shortlinks->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('reward') }} </h4>
                            <p> This Month earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> {{ $shortlinks->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('reward') }} </h4>
                            <p> Last Month earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
<!---- Shortlinks end ----->

<!---- PTC area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>PTC</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> {{ $pendingPtcAd->count() }} </h4>
                            <p> pending Approval </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> ${{ $ptcEarnings->sum('reward_per_view') }} </h4>
                            <p> Total Earned </p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $ptcEarnings->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('reward_per_view') }} </h4>
                            <p> This Month earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> ${{ $ptcEarnings->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('reward_per_view') }} </h4>
                            <p> Last Month earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
<!---- PTC end ----->

<!---- Faucet area Start ----->
<div class="container">
        <div class="all-stats-details">
            <div class="row">
                <h2>Faucet</h2>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-blue">
                        <div class="inner">
                            <h4> ${{ $faucet->sum('claimed_amount') }} </h4>
                            <p> Total earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-green">
                        <div class="inner">
                            <h4> ${{ $faucet->whereDate('created_at', Carbon\Carbon::today())->sum('claimed_amount') }} </h4>
                            <p> Today earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-orange">
                        <div class="inner">
                            <h4> ${{ $faucet->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('claimed_amount') }} </h4>
                            <p> This Month earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-box bg-red">
                        <div class="inner">
                            <h4> ${{ $faucet->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('claimed_amount') }} </h4>
                            <p> Last Month earnings</p>
                        </div>
                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
</div>
