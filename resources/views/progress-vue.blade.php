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
            <h5>@{{progress}}</h5>
            <hr>
            <h5>@{{title}}</h5>
            <hr>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                    role="progressbar" 
                    :aria-valuenow="progressPercentage" 
                    aria-valuemin="0" 
                    aria-valuemax="100" 
                    :style="`width: ${progressPercentage}%;`">
                    @{{progressPercentage}}%
                </div>
            </div>
        </div>
        {{-- <script src="https://unpkg.com/vue@3"></script> --}}
        {{-- <script src="https://unpkg.com/axios@1.1.2/dist/axios.min.js"></script> --}}
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            const app = {
                data(){
                    return {
                        progress: 'Progress page',
                        title: 'Progress of Uploads',
                        progressPercentage: 0,
                        params: {
                            id: null
                        }
                    }
                },
                methods: {
                    checkIfIdPresent(){
                        const urlSearchParams = new URLSearchParams(window.location.search);
                        const params = Object.fromEntries(urlSearchParams.entries());

                        if (params.id) {
                            this.params.id = params.id;
                        }
                    },
                    getUploadProgress(){
                        let self = this
                        this.checkIfIdPresent();

                        //Get progress data.
                        let progressResponse = setInterval(() => {
                            axios.get('/progress/data', {
                                params: {
                                    id: self.params.id ? self.params.id : "{{ session()->get('lastBatchId') }}",
                                }
                            }).then(function(response){
                                // console.log(response.data);
                                let totalJobs = parseInt(response.data.total_jobs);
                                let pendingJobs   = parseInt(response.data.pending_jobs);
                                let completedJobs = totalJobs - pendingJobs;

                                if (pendingJobs == 0) {
                                    self.progressPercentage = 100
                                } else {
                                    self.progressPercentage = 
                                    parseInt(completedJobs/totalJobs * 100).toFixed(0);
                                }

                                if (parseInt(self.progressPercentage) >= 100 || isNaN(self.progressPercentage)) {
                                    console.log('clearInterval: '+ self.progressPercentage);
                                    clearInterval(progressResponse);
                                }
                                console.log(self.progressPercentage);
                            })
                        }, 2000);
                    }
                },
                created() {
                    this.getUploadProgress();
                },
            }
            Vue.createApp(app).mount("#app")
        </script>
    </body>
</html>
