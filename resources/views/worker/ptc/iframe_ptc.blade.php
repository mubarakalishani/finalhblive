<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/891a7151bf.js" crossorigin="anonymous"></script>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>

    <title>ptc</title>
</head>
<body>
    
    @livewire('worker.ptc-iframe', ['uniqueId' => $uniqueId])

       <!-- Modal -->
       <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <script type="text/javascript">
                atOptions = {
                  'key' : 'ba877e7ce13ca00fb9e65ae3db928841',
                  'format' : 'iframe',
                  'height' : 60,
                  'width' : 468,
                  'params' : {}
                };
                document.write('<scr' + 'ipt type="text/javascript" src="//www.topcreativeformat.com/ba877e7ce13ca00fb9e65ae3db928841/invoke.js"></scr' + 'ipt>');
              </script>
              <form method="POST" {{ route('worker.ptc_iframe.submit', ['uniqueId' => $uniqueId]) }}>
                @csrf
                {{-- <div class="mb-3 text-center">
                  <div class="h-captcha" data-sitekey="{{ \App\Models\Setting::where('name', 'hcaptcha_site_key')->value('value') }}"></div>
                </div> --}}
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary" type="button">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      @if(session()->has('success'))
        <script>
            Swal.fire({
                title: "Good job!",
                text: "{{ session('success')}} ",
                icon: "success"
            });
        </script>
    @endif 
    
    @if(session()->has('error'))
        <script>
            Swal.fire({
                title: "Oops!",
                text: "{{ session('error')}} ",
                icon: "error"
            });
        </script>
    @endif 
    
<script>
  var adStarted;
  var Clock = {
    totalSeconds: 0,
    start: function (seconds) {
      this.totalSeconds = parseInt(seconds);
      var self = this;
      this.interval = setInterval(function () {
        document.getElementById('safeTimerDisplay').innerHTML = '00:' + self.totalSeconds;
        if (self.totalSeconds <= 0) {
          adStarted = 0;
          $('#exampleModalCenter').modal({keyboard: false, backdrop: 'static'})
          $('#exampleModalCenter').modal('show')
          clearInterval(self.interval);
        } else {
          self.totalSeconds -= 1;
        }
      }, 1000);
    },
    pause: function () {
      clearInterval(this.interval);
      delete this.interval;
    },
    resume: function () {
      if (!this.interval) this.start(this.totalSeconds);
    }
  };

  var timer = Object.create(Clock);

  window.addEventListener('blur', function() {
    if (adStarted == 1) {
      timer.pause();
    }  
  });

  window.addEventListener('focus', function() {
    if (adStarted == 1) {
      timer.resume();
    }
  });

  window.onload = function() {
    adStarted = 1;
    seconds = {{ $seconds }};
    timer.start(seconds);
  }

  // Function to check if at least half of the iframe content is loaded
  function isHalfLoaded() {
    var iframe = document.getElementById('adFrame');
    if (iframe) {
      var halfHeight = iframe.offsetHeight / 2;
      var scrollHeight = iframe.contentWindow.document.body.scrollHeight;
      return scrollHeight >= halfHeight;
    }
    return false;
  }

  // Listener for iframe load event
  document.getElementById('adFrame').addEventListener('load', function() {
    if (isHalfLoaded()) {
      // If at least half of the iframe content is loaded, start the timer
      timer.start({{ $seconds }});
    }
  });
</script>

      
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 
</body>
</html>