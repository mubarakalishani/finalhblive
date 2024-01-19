@include('includes.header-before-login')
    <!-- ***** Header Area End ***** -->

    <div class="all-page">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-content">
              <!-- ***** Banner Start ***** -->
              <div class="main-banner">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="header-text" style="margin-top: 60px; margin-bottom: 20px;">
                        <h6>Welcome To Handbucks</h6>
                        <h4><em>HandBucks</em> - Your Cash Oasis Online!</h4>
                        <p style="padding-bottom: 20px; black: white;">
                          Explore, earn, enjoy! HandBucks is your go-to for cash rewards. Complete surveys, click PTC ads, explore shortlinks, and more. Join now for a world of cash-earning opportunities!
                        </p>
                        <div class="main-button">
                          <a href="/register">Register Now</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-md-12 mt-5 d-flex justify-content-center signup-full-div">
                      <div class="col-lg-8 mb-6 p-0 position-relative full-box-bg">
                        <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                        <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                        <div class="card-1 home-signup-card">
                          <div class="card-body px-4 py-5 px-md-5">
                            <x-validation-errors class="mb-4" />
                            <form method="POST" action="{{ route('login') }}">
                              @csrf
                              <!-- Email input -->
                              <div class="form-outline mb-4">
                                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" placeholder="email address" />
                              </div>

                              <!-- Password input -->
                              <div class="form-outline mb-4">
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="password"/>
                              </div>
                              <!-- Submit button -->
                              <div class="singup-term-text">By signing in you agree to our terms and conditions as well as Privacy Policy. This site is protected by reCAPTCHA and the Google/hcaptcha Privacy Policy and Terms of Service apply.
                              </div>
                              <div class="signup-btn-icons-area">
                                <div class="row">
                                  <div class="col-lg-12 signup-btn">
                                    <button type="submit" class="btn btn-block mb-2">Login</button>
                                  </div>
                                </div>
                              </div>
                              <!-- Register buttons end -->
                            </form>
                            <div class="row">
                              <div class="col-lg-12 signup-btn">
                                <a href="/auth/google" class="btn bg-light btn-block"><img src="images/google.png">with google</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ***** Banner End ***** -->
              <!-- ***** Most Popular Features Start ***** -->
              <div class="container">
                <div class="most-popular-1">
                  <div class="container">
                    <div id="view-all-btn">
                      <div class="results-bar d-flex align-items-center justify-content-between">
                        <div class="heading-section-custom">
                          <h4><em>Most Popular</em> Features</h4>
                        </div>
                        <div class="d-flex">
                          <div class="ml-4">
                            <a href="login.html">View all</a>
                          </div>
                        </div>
                      </div>
                    </div>
                      <div class="row">
                        <div class="splide splide1">
                          <div class="splide__track">
                            <div class="splide__list">
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item inner-item">
                                    <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                    <h4>Micro Task<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> </li>
                                      <li> 393</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/lot.png" alt="">
                                    <h4>Surveys<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> </li>
                                      <li><i class="fa-solid fa-list"></i> 629</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                    <h4>Offerwalls<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-list"></i> 862</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                    <h4>Faucet<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-faucet-drip"></i> 7877</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/agm.png" alt="">
                                    <h4>PTC Ads<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-eye"></i> 484</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Shortlink<br><span>Proposals</span></h4>
                                    <ul>
                                    <li><i class="fa fa-star"></i> 4.8</li>
                                    <li><i class="fa-solid fa-link"></i> 843</li>
                                  </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Advertising<br><span>Buy ads pack</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-rectangle-ad"></i> ...</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Videos<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-brands fa-youtube"></i> 788</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Affiliating<br><span>Percentage</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-people-group"></i> 25%</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Games<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-gamepad"></i> 180</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item">
                                    <img src="https://www.aticlix.net/images/toro.png" alt="">
                                    <h4>Level system<br><span>Proposals</span></h4>
                                    <ul>
                                      <li><i class="fa fa-star"></i> 4.8</li>
                                      <li><i class="fa-solid fa-stairs"></i> 1..3</li>
                                    </ul>
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
              <!-- ***** Most Popular Provider End ***** -->
              <!-- ***** Most Popular Providers Start ***** -->
              <div class="container">
                <div class="most-popular-1">
                  <div class="container">
                      <div id="view-all-btn">
                        <div class="results-bar d-flex align-items-center justify-content-between">
                          <div class="heading-section-custom">
                            <h4><em>Most Popular</em> Provider</h4>
                          </div>
                          <div class="d-flex">
                            <div class="ml-4">
                              <a href="login.html">View all</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="splide splide2">
                          <div class="splide__track">
                            <div class="splide__list">
                              <div class="col-sm-2 splide__slide m-0">
                                <div class="">
                                  <div class="item inner-item">
                                    <img src="https://www.aticlix.net/images/wanna.png" alt="">
                                    <h4>Wannads<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                  <div class="">
                                    <div class="item">
                                      <img src="https://www.aticlix.net/images/lot.png" alt="">
                                      <h4>Lootably<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                  <div class="">
                                    <div class="item">
                                      <img src="https://www.aticlix.net/images/cpxs.png" alt="">
                                      <h4>CPX<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                  <div class="">
                                    <div class="item">
                                      <img src="https://www.aticlix.net/images/adsc.png" alt="">
                                      <h4>Moonlix<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                  <div class="">
                                    <div class="item">
                                      <img src="https://www.aticlix.net/images/agm.png" alt="">
                                      <h4>AdGatemedia<br><span><i class="fa-solid fa-circle" style="color:red;"></i> Availabe</span></h4>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-sm-2 splide__slide m-0">
                                  <div class="">
                                    <div class="item">
                                      <img src="https://www.aticlix.net/images/toro.png" alt="">
                                      <h4>Offertoro<br><span><i class="fa-solid fa-circle" style="color:#4acc4a;"></i> Availabe</span></h4>
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
              <!-- ***** Most Popular Provider End ***** -->
              <!-- ***** Most Popular payment gateways start ***** -->
              <div class="container">
                <div class="most-popular-1">
                  <div class="container">
                    <div id="view-all-btn">
                      <div class="results-bar d-flex align-items-center justify-content-between">
                        <div class="heading-section-custom">
                          <h4><em>Most Popular</em> Gateways</h4>
                        </div>
                        <div class="d-flex">
                          <div class="ml-4">
                            <a href="login.html">View all</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="splide splide3">
                        <div class="splide__track">
                          <div class="splide__list">
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                  <div class="item inner-item">
                                    <img src="https://www.dosurveys.net/assets/images/gateways/perfectmoney.png" alt="">
                                    <h4>PM<br><span>Minimum</span></h4>
                                  <ul>
                                    <li><i class="fa-solid fa-gauge-high"></i> Instant</li>
                                    <li><i class="fa fa-dollar"></i> 0.1</li>
                                  </ul>
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                <div class="item inner-item">
                                  <img src="https://www.dosurveys.net/assets/images/gateways/payeer.png" alt="">
                                  
                                  <h4>Payeer<br><span>Minimum</span></h4>
                                  <ul>
                                    <li><i class="fa-solid fa-gauge-high"></i> Instant</li>
                                    <li><i class="fa fa-dollar"></i> 0.1</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                <div class="item">
                                  <img src="https://www.dosurveys.net/assets/images/gateways/binance.png" alt="">
                                  <h4>Binance<br><span>Minimum</span></h4>
                                  <ul>
                                    <li><i class="fa-solid fa-gauge-high"></i> Instant</li>
                                    <li><i class="fa fa-dollar"></i> 0.1</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                <div class="item">
                                  <img src="https://www.dosurveys.net/assets/images/gateways/faucetpay.png" alt="">
                                  <h4>Faucetpay<br><span>Minimum</span></h4>
                                  <ul>
                                    <li><i class="fa-solid fa-gauge-high"></i> Instant</li>
                                    <li><i class="fa fa-dollar"></i> 0.1</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                <div class="item">
                                  <img src="https://www.dosurveys.net/assets/images/gateways/airtm.png" alt="">
                                  <h4>Airtm<br><span>Minimum</span></h4>
                                  <ul>
                                    <li><i class="fa-solid fa-gauge-high"></i> Instant</li>
                                    <li><i class="fa fa-dollar"></i> 0.1</li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2 splide__slide m-0">
                              <div class="">
                                <div class="item">
                                  <img src="https://www.dosurveys.net/assets/images/gateways/usdt.png" alt="">
                                  <h4>USDT<br><span>Minimum</span></h4>
                                  <ul>
                                    <li><i class="fa-solid fa-gauge-high"></i> Instant</li>
                                    <li><i class="fa fa-dollar"></i> 0.1</li>
                                  </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ***** Most Popular payment gateways End ***** -->
              <!-- ***** Withdraw and task history Start ***** -->
              <div class="gaming-library">
                <div class="row">
                  <div class="col-lg-6">
                    <div id="view-all-btn">
                      <div class="results-bar d-flex align-items-center justify-content-between">
                        <div class="heading-section-custom">
                          <h4>Last Cashouts</h4>
                        </div>
                        <div class="d-flex">
                          <div class="ml-4">
                            <a href="#">View all</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="paymentproof-content">
                      <form action="" method="#">
                        <div class="row">
                          <div class="col-lg-12 col-sm-6 col-md-6 mb-2">
                            <div class="card p-0 payment-proof-background">
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table no-wrap">
                                    <thead>
                                      <tr>
                                        <th class="border-top-0">Name</th>
                                        <th class="border-top-0">Amount</th>
                                        <th class="border-top-0">Method</th>
                                        <th class="border-top-0">Date</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <td class="txt-oflo">
                                            <span class="fi fi-us"></span> Mubarak
                                          </td>
                                          <td>
                                            <span class="text-success">$0.60</span>
                                          </td>
                                          <td>
                                            <span class="text-info">
                                              <img src="https://dosurveys.net/assets/images/gateways/binancepayid.png" >
                                            </span>
                                          </td>
                                          <td>
                                            <span class="text-warning">26 Sep 2023</span>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td class="txt-oflo">
                                            <span class="fi fi-us"></span>Usama
                                          </td>
                                          <td>
                                            <span class="text-success">$1.96</span>
                                          </td>
                                          <td>
                                            <span class="text-info">
                                              <img src="https://dosurveys.net/assets/images/gateways/binancepayid.png" >
                                            </span>
                                          </td>
                                          <td>
                                            <span class="text-warning">26 Sep 2023</span>
                                          </td>
                                        </tr>
                                        <tr>
                                            <td class="txt-oflo">
                                                <span class="fi fi-at"></span> Maxiaus
                                            </td>
                                            <td>
                                                <span class="text-success">$4.38</span>
                                            </td>
                                            <td>
                                                <span class="text-info">
                                                    <img src="https://dosurveys.net/assets/images/gateways/binancepayid.png" >
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-warning">26 Sep 2023</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <!-- More rows can be added here -->
                                        </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <div>
                                  <ul class="pagination d-flex justify-content-center">
                                    <li class="page-item">
                                      <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                      </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                      <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="col-lg-6 col-sm-6 col-md-6">
                    <div id="view-all-btn">
                      <div class="results-bar d-flex align-items-center justify-content-between">
                        <div class="heading-section-custom">
                          <h4>Last completed task</h4>
                        </div>
                        <div class="d-flex">
                          <div class="ml-4">
                            <a href="#">View all</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="paymentproof-content">
                      <form action="" method="#">
                        <div class="row">
                          <div class="col-lg-12 col-sm-4 col-md-4">
                            <div class="card p-0 payment-proof-background">
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table no-wrap">
                                    <thead>
                                      <tr>
                                        <th class="border-top-0">Name</th>
                                        <th class="border-top-0">Amount</th>
                                        <th class="border-top-0">Method</th>
                                        <th class="border-top-0">Date</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td class="txt-oflo">
                                          <span class="fi fi-us"></span>Mubarak
                                        </td>
                                        <td>
                                          <span class="text-success">$9.02</span>
                                        </td>
                                        <td>
                                          <span class="text-info">
                                              <img src="https://timewall.io/img/logo/TimeWall_logo_homepage_desktop.png" >
                                          </span>
                                        </td>
                                        <td>
                                          <span class="text-warning">26 Sep 2023</span>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="txt-oflo">
                                          <span class="fi fi-us"></span>Usama
                                        </td>
                                        <td>
                                          <span class="text-success">$1.96</span>
                                        </td>
                                        <td>
                                          <span class="text-info">
                                            <img src="https://lootably.com/img/logo.png" >
                                          </span>
                                        </td>
                                        <td>
                                          <span class="text-warning">26 Sep 2023</span>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="txt-oflo">
                                          <span class="fi fi-at"></span>Shaniali8
                                        </td>
                                        <td>
                                          <span class="text-success">$4.38</span>
                                        </td>
                                        <td>
                                          <span class="text-info">
                                              <img  src="https://www.lolsurveys.com/assets/images/offerwalls/ayetstudios.png" >
                                          </span>
                                        </td>
                                        <td>
                                          <span class="text-warning">26 Sep 2023</span>
                                        </td>
                                      </tr>
                                      <tr>
                                          <!-- More rows can be added here -->
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <div>
                                  <ul class="pagination d-flex justify-content-center">
                                    <li class="page-item">
                                      <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                      </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                      <span aria-hidden="true">&raquo;</span>
                                      <span class="sr-only">Next</span>
                                    </a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ***** Withdraw and task history End ***** -->
              <div class="most-popular-1 faqs-page">
                <div class="py-4 draggable">
                  <div class="container">
                      <div class="row justify-content-center">
                          <div class="col-lg-12">
                              <div class="card card-style1 border-0">
                                  <div class="card-body p-4 p-md-5 p-xl-6">
                                      <div class="text-center mb-2-3 mb-lg-6">
                                          <h2 class="h1 mb-0 text-secondary">Frequently Asked Questions</h2>
                                      </div>
                                      <div id="accordion" class="accordion-style">
                                        @foreach ($faqs as $faq)
                                          <div class="card mb-3">
                                              <div class="card-header" id="headingTwo">
                                                  <h5 class="mb-0">
                                                      <button class="btn btn-link @if($faq->s_no!=1) collapsed @endif" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->s_no }}" aria-expanded="@if($faq->s_no==1)true @else false @endif" aria-controls="collapse{{ $faq->s_no }}"><span class="text-theme-secondary me-2">Q.{{ $faq->s_no }}</span> {{ $faq->question }}</button>
                                                  </h5>
                                              </div>
                                              <div id="collapse{{ $faq->s_no }}" class="collapse @if($faq->s_no==1) show @endif" aria-labelledby="heading{{ $faq->s_no }}" data-bs-parent="#accordion">
                                                  <div class="card-body">
                                                    {{ $faq->answer }}
                                                  </div>
                                              </div>
                                          </div>
                                        @endforeach
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
      </div>
    </div>
    

    <! -- ***** Footer Start ***** -->
    @include('includes.footer-before-login')