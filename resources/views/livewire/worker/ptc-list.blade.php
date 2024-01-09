<div>
    <div class="container-fluid">

        <!-- all PTC ads start -->
        <div class="most-popular all-ptc-ads-page">
            <!-- ***** All PTC ads start ***** -->

            <div class="row align-items-end mb-4 pb-2">
                <div class="col-md-8">
                    <div class="section-title text-md-start">
                        <h4 class="title mb-4">Paid to Click Ads</h4>
                    </div>
                </div>
                <!--end col-->
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">Iframe</button>

                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Windows </button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row">
                            @foreach ($availableIframePtcAds as $ad)
                                @if (!$ad->remaining_hours || $ad->remaining_hours <= 0)
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-4 pt-2">
                                        <div class="card-body ">
                                            <div class="ads-para-description text-center" style="height: 100px;">
                                                <h6>{{ $ad->title }}</h6>
                                                <span>{{ $ad->description }}</span>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-4">
                                                    <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right"
                                                        title="average time required to solve this shortlink">
                                                        <i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $ad->seconds }}
                                                        sec
                                                    </span>
                                                </div>
                                                <div class="col-4">
                                                    <span class="text-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="The Amount You will earn for viewing this listing">
                                                        <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{
                                                        $ad->reward_per_view }}
                                                    </span>
                                                </div>

                                                <div class="col-4">
                                                    <span class="text-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="This is the time after which you can rewatch this ad.">
                                                        <i class="fa-solid fa-arrows-rotate"></i> {{ $ad->revision_interval
                                                        }}hrs
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="/views/iframe/{{ $ad->unique_id }}" target="_blank"
                                                class="form-control btn btn-primary">View ads</a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="row">
                            @foreach ($availableIframePtcAds as $ad)
                                @if ($ad->remaining_hours > 0)
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-4 pt-2">
                                        <div class="ptc-item">
                                            <div class="card-body ">
                                            <div class="ads-para-description text-center" style="height: 100px;">
                                                <h6>{{ $ad->title }}</h6>
                                                <span>{{ $ad->description }}</span>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-4">
                                                    <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right"
                                                        title="average time required to solve this shortlink">
                                                        <i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $ad->seconds }}
                                                        sec
                                                    </span>
                                                </div>
                                                <div class="col-4">
                                                    <span class="text-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="The Amount You will earn for viewing this listing">
                                                        <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{
                                                        $ad->reward_per_view }}
                                                    </span>
                                                </div>

                                                <div class="col-4">
                                                    <span class="text-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="This is the time after which you can rewatch this ad.">
                                                        <i class="fa-solid fa-arrows-rotate"></i> {{ $ad->revision_interval
                                                        }}hrs
                                                    </span>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="card-footer">
                                            <button
                                                class="form-control btn btn-secondary" disabled>wait {{ $ad->remaining_time }}</button>
                                            </div>
                                            </div>
                                        </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="row">
                            @foreach ($availableIframePtcAds as $ad)
                            <div class="col-lg-3 col-md-6 col-sm-12 mt-4 pt-2">
                                <div class="card-body ">
                                    <div class="ads-para-description text-center" style="height: 100px;">
                                        <h6>{{ $ad->title }}</h6>
                                        <span>{{ $ad->description }}</span>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-4">
                                            <span class="text-info" data-bs-toggle="tooltip" data-bs-placement="right"
                                                title="average time required to solve this shortlink">
                                                <i class="fa-solid fa-clock" aria-hidden="true"></i> {{ $ad->seconds }}
                                                sec
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <span class="text-primary" data-bs-toggle="tooltip"
                                                data-bs-placement="right"
                                                title="The Amount You will earn for viewing this listing">
                                                <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i> {{
                                                $ad->reward_per_view }}
                                            </span>
                                        </div>

                                        <div class="col-4">
                                            <span class="text-success" data-bs-toggle="tooltip"
                                                data-bs-placement="right"
                                                title="This is the time after which you can rewatch this ad.">
                                                <i class="fa-solid fa-arrows-rotate"></i> {{ $ad->revision_interval
                                                }}hrs
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                        <a href="{{ $ad->url }}" onclick="startTimer('{{ $ad->seconds }}')" id="startTimerLink" target="_blank"
                                            class="form-control btn btn-primary">View ads
                                        </a>
                                        <h6 id="safeTimerDisplay" class="text-center" style="display: none;">Time Left: seconds</h6>
                                       
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">This is
                    our third nav</div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <form method="POST" wire:submit.prevent="verifyAndUpdate">
                    @csrf
                    <div class="mb-3 text-center">
                          {!! Captcha::display() !!}
                    </div>
                    <div class="d-grid gap-2">
                      <button type="submit" class="btn btn-primary" type="button">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
    </div>
    <script>
    var adStarted;
    var Clock = {
        totalSeconds: 0, // initial value of the timer, will be updated later
        start: function (seconds) {
            // update the totalSeconds property with the parameter
            this.totalSeconds = parseInt(seconds);
            var self = this;
            this.interval = setInterval(function () {
                document.getElementById('safeTimerDisplay').innerHTML = '00:' + self.totalSeconds;
                if (self.totalSeconds <= 0) {
                    adStarted = 0;
                    $('#exampleModalCenter').modal({keyboard: false, backdrop: 'static'})
                    $('#exampleModalCenter').modal('show')
                    clearInterval(self.interval);
                }else{
                    self.totalSeconds -= 1;
                }
            }, 1000);
            document.getElementById('startTimerLink').style.display = 'none';
            document.getElementById('safeTimerDisplay').style.display = 'block';
        },
        pause: function () {
            clearInterval(this.interval);
            delete this.interval;
        },
        resume: function () {
            if (!this.interval) this.start(this.totalSeconds); // pass the current value of totalSeconds
        }
    };

    var timer = Object.create(Clock);
    function startTimer(seconds){
        adStarted = 1;
        // start the timer with the value of from the onclick attribute
        timer.start(seconds);
    }


    // pause the timer when the window gains focus
    window.addEventListener('focus', function() {
        if(adStarted==1){
            timer.pause();
        }  
    });

    // resume the timer when the window loses focus
    window.addEventListener('blur', function() {
        if(adStarted==1){
            timer.resume();
        }
  
    });

    </script>
</div>