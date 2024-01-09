@extends('layouts.afterlogin')
@section('content')
<div class="container-fluid">

    <!-- user dashboard start -->
    <div class="all-history-page all-surveys-page">
        <!-- ***** My jobs Start ***** -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12 my-4">
                    <div class="heading-section">
                        <h4><em>Most Popular</em> Offerwalls</h4>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-sm-4">
                            <div class="card-item">
                                <img src="images/bananatic.png" alt="">
                                <h4>Game Gardens<br><span>Lootably</span><br>
                                    <span1>$0.10</span1>
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="card-item">
                                <img src="images/cint.png" alt="">
                                <h4>Game Gardens<br><span>Lootably</span><br>
                                    <span1>$0.10</span1>
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="card-item">
                                <img src="images/usama.png" alt="">
                                <h4>Hayat Finans<br><span>Lootably</span><br>
                                    <span1>$0.10</span1>
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="card-item">
                                <img src="images/popular-01.jpg" alt="">
                                <h4>Hayat Finans<br><span>Lootably</span><br>
                                    <span1>$0.10</span1>
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="card-item">
                                <img src="images/oppepl.png" alt="">
                                <h4>Hayat Finans<br><span>Lootably</span><br>
                                    <span1>$0.10</span1>
                                </h4>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="card-item">
                                <img src="images/usama.png" alt="">
                                <h4>Hayat Finans<br><span>Lootably</span><br>
                                    <span1>$0.10</span1>
                                </h4>
                            </div>
                        </div>
                        <!-- ***** Most Popular Features End ***** -->

                    </div>
                </div>
            </div>
        </div>
        <!-- My jobs end -->
    </div>
    <div class="all-history-page all-surveys-page">
        <div class="most-popular-1">
            <div id="view-all-btn">
                <div class="results-bar d-flex align-items-center justify-content-between">
                    <div class="heading-section-custom">
                        <h4><em>Offers</em> Providers</h4>
                    </div>
                    <div class="d-flex">
                        <div class="ml-4">
                            <a href="all-offers.html">View all</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="splide splide4">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item inner-item">
                                        <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                        <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i>
                                                Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item">
                                        <img src="https://www.aticlix.net/images/lot.png" alt="">
                                        <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i>
                                                Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item">
                                        <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                        <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i>
                                                Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item">
                                        <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                        <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i>
                                                Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item">
                                        <img src="https://www.aticlix.net/images/agm.png" alt="">
                                        <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i>
                                                Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                    <div class="item">
                                        <img src="https://www.aticlix.net/images/toro.png" alt="">
                                        <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i>
                                                Availabe</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection