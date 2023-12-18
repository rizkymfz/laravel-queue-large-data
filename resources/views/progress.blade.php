<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('bootstrap.min.css') }}">
        
        <!-- Styles -->
        <style>
        </style>
    </head>
    <body class="antialiased">
        @component('components.header')
        @endcomponent
        <div class="container mt-5" id="app">
            {{-- <h5>@{{progress}}</h5>
            <hr>
            <h5>@{{title}}</h5> --}}
            <hr>
            {{-- @if(!is_null($batch)) --}}
            <div class="mt-4 import-div" style="display: none">
                Uploading
                <br>
                <span id="processedJob">0</span>  completed out of <span id="totalJob">0</span>
                <span id="progressJob">(0%)</span>
            </div>
            {{-- @endif --}}
            {{-- <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                    role="progressbar" 
                    :aria-valuenow="progressPercentage" 
                    aria-valuemin="0" 
                    aria-valuemax="100" 
                    :style="`width: ${progressPercentage}%;`">
                    @{{progressPercentage}}%
                </div>
            </div> --}}
        </div>
        {{-- <script src="https://unpkg.com/vue@3"></script> --}}
        {{-- <script src="https://unpkg.com/axios@1.1.2/dist/axios.min.js"></script> --}}
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @if(session()->has('lastBatchId'))
            <script type="text/javascript">

                $('.import-div').show()
                const callData = setInterval(() => {
                    axios.get('/progress/call-batch-progress', {
                        params: {
                            id: "{{ session()->get('lastBatchId') }}",
                        }
                    }).then(function(response){
                        console.log(response)
                        $('#processedJob').html(response.data.processedJob)
                        $('#totalJob').html(response.data.totalJob)
                        $('#progressJob').html(response.data.progressJob +'%')
                        if (parseInt(response.data.progressJob) == 100) {
                            console.log('clearInterval: '+ self.progressPercentage);
                            clearInterval(callData);
                        }
                    })
                }, 2000);

                // $(function() {
                //     const lastBatchId = "{{ session()->get('lastBatchId') }}"
                //     console.log(lastBatchId)

                //     if (lastBatchId != 'undefined') {
                //         callData()
                //     }
                // })
            </script>
        @else
            <script>
                $('.import-div').hide()
            </script>
        @endif
    </body>
</html>
