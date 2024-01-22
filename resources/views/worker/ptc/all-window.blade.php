@extends('layouts.afterlogin')
@section('content')
    <div class="container-fluid">
        <div class="most-popular all-ptc-ads-page">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="/views/iframe">Iframe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/views/window">Windows</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/views/youtube">Youtube</a>
                  </li>
            </ul>
              <div class="row">
                @foreach ($availableWindowPtcAds as $ad)
                    @if (!$ad->totalMinutesDifference || $ad->totalMinutesDifference > ($ad->revision_interval * 60))
                        <div class="col-lg-3 col-md-6 col-sm-12 mt-4 pt-2">
                            <div class="card-body ">
                                <div class="ads-para-description text-center" style="height: 100px;">
                                    <h6>{{ $ad->title }}</h6>
                                    <span style="font-size: 0.8rem !important">{{ $ad->description }}</span>
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
                                <a href="{{ $ad->url }}" onclick="startTimer('{{ $ad->seconds }}', '{{ $ad->unique_id }}')" id="startTimerLink" target="_blank"
                                    class="form-control view-window btn btn-primary">View ads
                                </a>
                                <h6 id="safeTimerDisplay" class="text-center" style="display: none;">Time Left: seconds</h6>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="row">
                @foreach ($availableWindowPtcAds as $ad)
                    @if ( $ad->totalMinutesDifference && $ad->totalMinutesDifference < ($ad->revision_interval * 60))
                        <div class="col-lg-3 col-md-6 col-sm-12 mt-4 pt-2">
                            <div class="ptc-item">
                                <div class="card-body ">
                                <div class="ads-para-description text-center" style="height: 100px;">
                                    <h6>{{ $ad->title }}</h6>
                                    <span>{{ $ad->description }} $ad->totalMinutesDifference = {{$ad->totalMinutesDifference}}</span>
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
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <form method="POST" {{ route('worker.ptc.window.submit') }}>
                @csrf
                <div class="mb-3 text-center">
                    <div class="h-captcha" data-sitekey="{{ \App\Models\Setting::where('name', 'hcaptcha_site_key')->value('value') }}"></div>
                </div>
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="additionalField1" name="id">
                 </div>
                 <div class="mb-3">
                    <label for="additionalField2">Additional Field 2</label>
                    <input type="text" class="form-control" id="additionalField2" name="additionalField2">
                 </div>
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary" type="button">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
    <script>
        var adStarted;
        var Clock = {
            totalSeconds: 0, // initial value of the timer, will be updated later
            start: function (seconds, id) {
                // update the totalSeconds property with the parameter
                this.totalSeconds = parseInt(seconds);
                var self = this;
                this.interval = setInterval(function () {
                    document.getElementById('safeTimerDisplay').innerHTML = '00:' + self.totalSeconds;
                    if (self.totalSeconds <= 0) {
                        adStarted = 0;
                        // Set values for additional input fields
                        var valueForField1 = id; // Replace with actual value
                        var valueForField2 = "Value 2"; // Replace with actual value

                        // Set values for the input fields
                        $('#additionalField1').val(valueForField1);
                        $('#additionalField2').val(valueForField2);

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
        function startTimer(seconds, id){
            var disableLinks = document.querySelectorAll('.view-window');

            // Add an onclick event to each matching element
            disableLinks.forEach(function(link) {
                link.classList.add('disabled');
            });
            adStarted = 1;
            // start the timer with the value of from the onclick attribute
            timer.start(seconds, id);
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
@endsection    
