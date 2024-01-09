<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/891a7151bf.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>ptc</title>
</head>
<body>
    
    @livewire('worker.ptc-iframe', ['uniqueId' => $uniqueId])

       <!-- Modal -->
       <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <form method="POST" {{ route('worker.ptc_iframe.submit', ['uniqueId' => $uniqueId]) }}>
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

      
      {{-- <script>
var timer;
var sec = {{ $seconds }};

function startTimer() {
  timer = setInterval(function() {
    document.getElementById('safeTimerDisplay').innerHTML='00:'+sec;
    if (sec <= 0) {
      $('#exampleModalCenter').modal({keyboard: false, backdrop: 'static'})
      $('#exampleModalCenter').modal('show')
      clearInterval(timer)
    } else{
      sec--;
    }
    
  }, 1000);
}


window.addEventListener('blur', function() {
  clearInterval(timer);
});

window.addEventListener('focus', function() {
  startTimer();
});

window.onload = function() {
  startTimer();
}

      </script> --}}







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


  // pause the timer when the window gains focus
  window.addEventListener('blur', function() {
      if(adStarted==1){
          timer.pause();
      }  
  });

  // resume the timer when the window loses focus
  window.addEventListener('focus', function() {
      if(adStarted==1){
          timer.resume();
      }

  });


  window.onload = function() {
    adStarted = 1;
    seconds = {{ $seconds }};
    timer.start(seconds);
  }

  </script>

      
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 
</body>
</html>