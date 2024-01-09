@extends('layouts.afterlogin')
@section('content')
<div class="container-fluid">


    <!-- all games start -->
    <div class="all-history-page">
        <div class="container">
            <div class="pt-3">
                <h4><em>History</em></h4>
            </div>
            <div class="row align-items-end mb-4 pb-2">
                <div class="col-md-8">
                </div>
                <!--end col-->
                <nav class="mt-3">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">Overall Earn</button>
                        <button class="nav-link" id="nav-withdraw-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-withdraw" type="button" role="tab" aria-controls="nav-withdraw"
                            aria-selected="false">Withdrawals</button>
                        <button class="nav-link" id="nav-bonus-tab" data-bs-toggle="tab" data-bs-target="#nav-bonus"
                            type="button" role="tab" aria-contsrols="nav-bonus" aria-selected="false">Bonus</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="history-data mt-3">
                            <div>
                                <div class="results-bar d-flex align-items-center justify-content-between mb-4">

                                    <div class="d-flex results-bar__search">
                                        <form action="#" method="get" class="d-flex align-items-center"
                                            id="job-search-form">
                                            <input type="hidden" name="showDeclined" id="showDeclined" value="Y">
                                            <input type="hidden" name="filter" id="filter" value="">
                                            <input type="hidden" name="sort" id="sort" value="NEWEST">
                                            <input type="hidden" name="Folder" id="Folder" value="DEFAULT">
                                            <input type="text" name="s" id="search-term" class="form-control"
                                                placeholder="search here" value="">
                                            <input type="submit" class="btn btn-icon text-danger p-0 d-none">
                                        </form>
                                    </div>
                                </div>

                                <div class="results-bar d-flex align-items-center justify-content-between flex-wrap">
                                    <p class="mb-0 mr-4 results-bar__total"><span>2</span> results</p>
                                    <div class="d-flex flex-wrap">
                                        <!--<div class="list-filter dropdown mr-4">
                                                  <a class="dropdown-toggle btn btn-link" href="#" role="button" id="dropdownMenuCountery" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  Show declined <span class="list-filter__sep">/</span> <span class="list-filter__selection">Yes</span>
                                                  </a>

                                                  <div class="dropdown-menu">
                                                    <a class="dropdown-item job-search-form" data-filter="showDeclined" data-filter-value="Y">Yes</a>
                                                    <a class="dropdown-item job-search-form" data-filter="showDeclined" data-filter-value="N">No</a>
                                                  </div>

                                              </div> -->
                                        <div class="list-filter dropdown" data-job-filter="sort">
                                            <a class="dropdown-toggle btn btn-link" href="#" role="button"
                                                id="dropdownMenuCountery" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Filter by <span class="list-filter__sep">/</span>
                                                <span class="list-filter__selection">
                                                    All Jobs

                                                </span>
                                            </a>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item active job-search-form" data-filter="filter"
                                                    data-filter-value="">All Jobs</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="PENDING_REVIEW">Micro task</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="RUNNING">Offerwalls</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="PAUSED_ADMIN">Surveys</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="PAUSED">Faucet</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="PAUSED_SYSTEM">Shortlink</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="FINISHED">PTC</a>
                                                <a class="dropdown-item job-search-form" data-filter="filter"
                                                    data-filter-value="BLOCKED">Paid Games</a>
                                            </div>
                                        </div>
                                        <div class="list-filter dropdown" data-job-filter="sort">
                                            <a class="dropdown-toggle btn btn-link" href="#" role="button"
                                                id="dropdownMenuCountery" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Sort by <span class="list-filter__sep">/</span>
                                                <span class="list-filter__selection">
                                                    All
                                                </span>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item active job-search-form" data-filter="sort"
                                                    data-filter-value="NEWEST">Complete</a>
                                                <a class="dropdown-item job-search-form" data-filter="sort"
                                                    data-filter-value="OLDEST">Pendding</a>
                                                <a class="dropdown-item job-search-form" data-filter="sort"
                                                    data-filter-value="COST">Canceled</a>
                                                <a class="dropdown-item job-search-form" data-filter="sort"
                                                    data-filter-value="TITLE">Reversed</a>
                                                <a class="dropdown-item job-search-form" data-filter="sort"
                                                    data-filter-value="TITLE">Newest</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- top area-before-table end-->

                                <div class="table-responsive-md">
                                    <table class="table table-big table-hover table-middle">
                                        <thead>
                                            <tr class="table-row">
                                                <!-- <th scope="col" class="white-space-pre text-center">Status</th> -->

                                                <th scope="col" class="white-space-pre">Job Type</th>
                                                <th scope="col" class="white-space-pre text-center">Progress</th>
                                                <th scope="col" class="white-space-pre text-center">Reward</th>
                                                <th scope="col" class="white-space-pre text-center">Detail</th>
                                                <th scope="col" class="white-space-pre text-center">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jobs-list">
                                            <tr class="table-row clickable">
                                                <!-- <td scope="row" class="table-cell-status text-center">
                                                          <i class="fa fa-times" aria-hidden="true"></i>
                                                        </td> -->

                                                <td class="table-cell-name">
                                                    <a href="">Offerwall</a>
                                                </td>
                                                <td scope="row" class="table-cell-status text-center">
                                                    <i class="fa-regular fa-circle-xmark"></i> Canceled
                                                </td>
                                                <td class="table-cell-progress text-center">$0.13</td>
                                                <td class="table-cell-settings p-0 text-center">
                                                    <a href="">
                                                        <i class="fa fa-eye side-icons" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <td class="table-cell-rated text-center">04-12-2023</td>
                                            </tr>

                                            <tr class="table-row clickable">
                                                <td class="table-cell-name" style="min-width: 150px;">
                                                    <a href="#">Survey</a>
                                                </td>
                                                <td scope="row" class="table-cell-status text-center">
                                                    <i class="fa-regular fa-circle-check"></i> Complete
                                                </td>
                                                <td class="table-cell-rated text-center">$1.2</td>


                                                <td class="table-cell-settings p-0 text-center">
                                                    <a href="">
                                                        <i class="fa fa-eye side-icons" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <td class="table-cell-rated text-center">04-12-2023</td>
                                            </tr>
                                            <tr class="table-row clickable">
                                                <!-- <td scope="row" class="table-cell-status text-center">
                                                          <i class="fa fa-times" aria-hidden="true"></i>
                                                        </td> -->

                                                <td class="table-cell-name">
                                                    <a href="">Micro job</a>
                                                </td>
                                                <td scope="row" class="table-cell-status text-center">
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i> Pendding
                                                </td>
                                                <td class="table-cell-progress text-center">$0.13</td>
                                                <td class="table-cell-settings p-0 text-center">
                                                    <a href="">
                                                        <i class="fa fa-eye side-icons" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <td class="table-cell-rated text-center">04-12-2023</td>
                                            </tr>
                                            <tr class="table-row clickable">
                                                <td class="table-cell-name" style="min-width: 150px;">
                                                    <a href="#">Survey</a>
                                                </td>
                                                <td scope="row" class="table-cell-status text-center">
                                                    <i class="fa-solid fa-ban"></i> Reversed
                                                </td>
                                                <td class="table-cell-rated text-center">$1.2</td>


                                                <td class="table-cell-settings p-0 text-center">
                                                    <a href="">
                                                        <i class="fa fa-eye side-icons" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <td class="table-cell-rated text-center">04-12-2023</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- demo -->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-withdraw" role="tabpanel" aria-labelledby="nav-withdraw-tab">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="history-data mt-3">
                                <div>
                                    <div
                                        class="results-bar d-flex align-items-center justify-content-between flex-wrap">
                                        <p class="mb-0 mr-4 results-bar__total"><span>2</span> results</p>
                                        <div class="d-flex flex-wrap">
                                            <div class="list-filter dropdown" data-job-filter="sort">
                                                <a class="dropdown-toggle btn btn-link" href="#" role="button"
                                                    id="dropdownMenuCountery" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Filter by <span class="list-filter__sep">/</span>
                                                    <span class="list-filter__selection">
                                                        All

                                                    </span>
                                                </a>

                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item active job-search-form" data-filter="filter"
                                                        data-filter-value="">All</a>
                                                    <a class="dropdown-item job-search-form" data-filter="filter"
                                                        data-filter-value="PENDING_REVIEW">Complete</a>
                                                    <a class="dropdown-item job-search-form" data-filter="filter"
                                                        data-filter-value="RUNNING">Pendding</a>
                                                    <a class="dropdown-item job-search-form" data-filter="filter"
                                                        data-filter-value="PAUSED_ADMIN">Canceled</a>

                                                </div>
                                            </div>
                                            <div class="list-filter dropdown" data-job-filter="sort">
                                                <a class="dropdown-toggle btn btn-link" href="#" role="button"
                                                    id="dropdownMenuCountery" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Sort by <span class="list-filter__sep">/</span>
                                                    <span class="list-filter__selection">
                                                        Method
                                                    </span>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item active job-search-form" data-filter="sort"
                                                        data-filter-value="NEWEST">All</a>
                                                    <a class="dropdown-item  job-search-form" data-filter="sort"
                                                        data-filter-value="NEWEST">Payeer</a>
                                                    <a class="dropdown-item  job-search-form" data-filter="sort"
                                                        data-filter-value="NEWEST">Faucetpay</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="OLDEST">Airtm</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="COST">Perfect money</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="TITLE">Binance pay id</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="TITLE">USDT Polygone</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="TITLE">USDT BEP-20</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="TITLE">Gift card</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="TITLE">Web-Money</a>
                                                    <a class="dropdown-item job-search-form" data-filter="sort"
                                                        data-filter-value="TITLE">Payonner</a>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- top area-before-table end-->

                                    <div class="table-responsive-md">
                                        <table class="table table-big table-hover table-middle">
                                            <thead>
                                                <tr class="table-row">
                                                    <!-- <th scope="col" class="white-space-pre text-center">Status</th> -->

                                                    <th scope="col" class="white-space-pre">Payment Method</th>
                                                    <th scope="col" class="white-space-pre text-center">Status</th>
                                                    <th scope="col" class="white-space-pre text-center">Amount</th>
                                                    <th scope="col" class="white-space-pre text-center">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="jobs-list">
                                                <tr class="table-row clickable">
                                                    <!-- <td scope="row" class="table-cell-status text-center">
                                                              <i class="fa fa-times" aria-hidden="true"></i>
                                                            </td> -->

                                                    <td class="table-cell-name">
                                                        Faucetpay
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-regular fa-circle-xmark"></i> Canceled
                                                    </td>
                                                    <td class="table-cell-progress text-center">$0.13</td>
                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>

                                                <tr class="table-row clickable">
                                                    <td class="table-cell-name" style="min-width: 150px;">
                                                        Payeer
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-regular fa-circle-check"></i> Complete
                                                    </td>
                                                    <td class="table-cell-rated text-center">$1.2</td>

                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>
                                                <tr class="table-row clickable">
                                                    <!-- <td scope="row" class="table-cell-status text-center">
                                                              <i class="fa fa-times" aria-hidden="true"></i>
                                                            </td> -->

                                                    <td class="table-cell-name">
                                                        Binance pay Id
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i> Pendding
                                                    </td>
                                                    <td class="table-cell-progress text-center">$0.13</td>
                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>
                                                <tr class="table-row clickable">
                                                    <td class="table-cell-name" style="min-width: 150px;">
                                                        Airtm
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-solid fa-ban"></i> Reversed
                                                    </td>
                                                    <td class="table-cell-rated text-center">$1.2</td>
                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- demo -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-bonus" role="tabpanel" aria-labelledby="nav-bonus-tab">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="history-data mt-3">
                                <div>
                                    <div
                                        class="results-bar d-flex align-items-center justify-content-between flex-wrap">
                                        <p class="mb-0 mr-4 results-bar__total"><span>2</span> results</p>
                                        <div class="d-flex flex-wrap">
                                            <div class="list-filter dropdown" data-job-filter="sort">
                                                <a class="dropdown-toggle btn btn-link" href="#" role="button"
                                                    id="dropdownMenuCountery" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Filter by <span class="list-filter__sep">/</span>
                                                    <span class="list-filter__selection">
                                                        All

                                                    </span>
                                                </a>

                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item active job-search-form" data-filter="filter"
                                                        data-filter-value="">All</a>
                                                    <a class="dropdown-item job-search-form" data-filter="filter"
                                                        data-filter-value="PENDING_REVIEW">Today</a>
                                                    <a class="dropdown-item job-search-form" data-filter="filter"
                                                        data-filter-value="RUNNING">Yesterday</a>
                                                    <a class="dropdown-item job-search-form" data-filter="filter"
                                                        data-filter-value="PAUSED_ADMIN">Last Week</a>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- top area-before-table end-->

                                    <div class="table-responsive-md">
                                        <table class="table table-big table-hover table-middle">
                                            <thead>
                                                <tr class="table-row">
                                                    <!-- <th scope="col" class="white-space-pre text-center">Status</th> -->

                                                    <th scope="col" class="white-space-pre">Bonus Title</th>
                                                    <th scope="col" class="white-space-pre text-center">Status</th>
                                                    <th scope="col" class="white-space-pre text-center">Amount</th>
                                                    <th scope="col" class="white-space-pre text-center">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="jobs-list">
                                                <tr class="table-row clickable">
                                                    <!-- <td scope="row" class="table-cell-status text-center">
                                                              <i class="fa fa-times" aria-hidden="true"></i>
                                                            </td> -->

                                                    <td class="table-cell-name">
                                                        Login Bonus
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-regular fa-circle-check"></i> Complete
                                                    </td>
                                                    <td class="table-cell-progress text-center">$0.13</td>
                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>

                                                <tr class="table-row clickable">
                                                    <td class="table-cell-name" style="min-width: 150px;">
                                                        Complete single offer
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-regular fa-circle-check"></i> Complete
                                                    </td>
                                                    <td class="table-cell-rated text-center">$1.2</td>

                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>
                                                <tr class="table-row clickable">
                                                    <!-- <td scope="row" class="table-cell-status text-center">
                                                              <i class="fa fa-times" aria-hidden="true"></i>
                                                            </td> -->

                                                    <td class="table-cell-name">
                                                        Referrer a friend
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-regular fa-circle-check"></i> Complete
                                                    </td>
                                                    <td class="table-cell-progress text-center">$0.13</td>
                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>
                                                <tr class="table-row clickable">
                                                    <td class="table-cell-name" style="min-width: 150px;">
                                                        Complete a faucet claim
                                                    </td>
                                                    <td scope="row" class="table-cell-status text-center">
                                                        <i class="fa-regular fa-circle-check"></i> Complete
                                                    </td>
                                                    <td class="table-cell-rated text-center">$1.2</td>
                                                    <td class="table-cell-rated text-center">04-12-2023</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- demo -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-bonus" role="tabpanel" aria-labelledby="nav-bonus-tab">This is
                        our fourth tab it's still free</div>
                </div>
            </div>
            <!--end row-->
        </div>

        <!-- All games end -->
    </div>
</div>