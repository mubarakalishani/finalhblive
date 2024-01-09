<div>
    <div>
        {{-- <h1>Time Remaining: {{ $timeRemaining }}</h1> --}}
        <p id="safeTimerDisplay">Loading:</p>
    </div>
    <iframe id="adFrame" src="{{ $url }}" width="100%" height="1000px"></iframe>


 




    @livewireScripts
    {{-- <script>
        var timer;
        window.addEventListener('load', function() 
        {
            startTimer();
        });
        function startTimer() 
        {
            timer = setInterval(function() 
            {
                @this.call('startTimer');
                console.log('{{$timeRemaining}}');
     
            }, 1000);

            console.log('{{$timeRemaining}}')
        }

        function pauseTimer() 
        {
            clearInterval(timer);
        }

        function resumeTimer() {
            startTimer();
        }

        window.addEventListener('blur', function() 
        {
            pauseTimer();
        });

        window.addEventListener('focus', function() 
        {
            resumeTimer();
        });
    </script> --}}
</div>



