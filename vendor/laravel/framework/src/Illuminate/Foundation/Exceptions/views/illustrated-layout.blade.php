<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" >
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/_all-skins.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/checkflu.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/abastecimiento.css') }}">
        <style type="text/css">
            .absolute {
                position: absolute;
            }

            .pin {
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }

            .bg-no-repeat {
                background-repeat: no-repeat;
            }

            .bg-black {
                background-color: #191919;
            }

            .bg-yellow {
                background-color: #f1c711;
            }

            .bg-gray {
                background-color: #d7d7d7;
            }

            @media (min-width: 768px) {
                .md\:bg-left {
                    background-position: left;
                }

                .md\:bg-right {
                    background-position: right;
                }

                .md\:flex {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                }

                .md\:my-6 {
                    margin-top: 1.5rem;
                    margin-bottom: 1.5rem;
                }

                .md\:min-h-screen {
                    min-height: 100vh;
                }

                .md\:pb-0 {
                    padding-bottom: 0;
                }

                .md\:text-3xl {
                    font-size: 1.875rem;
                }

                .md\:text-15xl {
                    font-size: 9rem;
                }

                .md\:w-1\/2 {
                    width: 50%;
                }
            }

            @media (min-width: 992px) {
                .lg\:bg-center {
                    background-position: center;
                }
            }

        </style>
    </head>
    <body class="antialiased font-sans">
        <div class="content-wrapper" style="margin: 0 !important;padding: 0 !important">
            <section class="content-header">
                @include('layouts.nav')
                <br><br>
            </section>
                
            <section class="content">
                <div class="col-xs-12">
                    <div class="row" style="display: block">
                        <section id="contact" class="section-bg wow">
                            @yield('image')
                        </section>
                    </div>
                </div>
            </section>
                
        </div>
       
       <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <script src="{{ asset('js/jquery.number.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-select.js') }}"></script>
        <script src="{{ asset('js/checkflu.js') }}"></script>
        <script src="{{ asset('js/superfish.js') }}"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-127983785-1"></script>
        <script src="../js/navbar.js"></script>

    </body>
    @include('layouts.footer')
</html>
