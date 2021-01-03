<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .error {
            border-color: #dc3545
        }
        .flex-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .on {
            display: inline;
        }
        .off {
            display: none;
        }

        .show_password {
            position: relative;
        }

        .show_password_btn {
            position: absolute;
            right: 15px;
            top: -28px;
            cursor: pointer
        }
        .show_password_btn:hover {
            color: blue;
        }
    </style>

</head>


<body id="page-top">
    <div class="flex-container">
                <div class="card p-4 border-0 shadow-sm animate__animated animate__flipInX" style="width: 25vw">
                    <img src="{{ asset('images/default.svg') }}" class="mx-auto d-block" alt="" style="width: 50%;">
                    <!-- <hr> -->
                    <form id="form-login" action="{{ route('process.login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control rounded-pill outline-danger" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control rounded-pill" required>
                            <div class="show_password">
                                <div class="show_password_btn">
                                    <i data-feather="eye" class="off"></i>
                                    <i data-feather="eye-off" class="on"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="rememberMe" value="true">
                                <label class="custom-control-label" for="rememberMe">Ingat saya.</label>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button id="login" class="btn btn-sm rounded-pill btn-outline-primary mb-3 pr-3 pl-3">Masuk</button>
                        </div>

                        @if(count($errors))
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Invalid Credentials!</strong> Periksa kembali email dan password anda.
                        </div>
                        @endif

                        <div class="text-muted text-right">
                            <!-- <small><a href="">Forget password?</a></small> -->
                        </div>
                    </form>
                </div>
            </div>

    <!-- Bootstrap core JavaScript-->
    <!-- jQuery -->
    <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- lazyload plugin -->
    <script src="{{ asset('assets/js/lazyload.config.js') }}"></script>
    <script src="{{ asset('assets/js/lazyload.js') }}"></script>
    <script src="{{ asset('assets/js/plugin.js') }}"></script>
    <!-- scrollreveal -->
    <script src="{{ asset('libs/scrollreveal/dist/scrollreveal.min.js') }}"></script>
    <!-- feathericon -->
    <script src="{{ asset('libs/feather-icons/dist/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feathericon.js') }}"></script>
    <!-- theme -->
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/utils.js') }}"></script>
    <!-- endbuild -->

    <!-- axios -->
    <script src="{{ asset('js/axios.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.show_password_btn').click(function () {
                if ($('#password').attr('type') == 'text') {
                    $('#password').attr('type', 'password')
                    $('.on').css('display', 'inline');
                    $('.off').css('display', 'none');
                } else{
                    $('#password').attr('type', 'text')
                    $('.on').css('display', 'none');
                    $('.off').css('display', 'inline');
                }
                
            })

            $(document).on('submit', '#form-login', function(e) {
                e.preventDefault()
            })

            // login
            $(document).on('click', '#login', function() {
                $('#login').text('Memeriksa...');
                axios.post('/login', {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    rememberMe: $('#rememberMe').prop('checked'),
                }).then(function(response) {
                    if (response.data.status) {
                        if (response.data.role === 'admin') {
                            location.href = "{{route('dashboard.index')}}"
                        } else if (response.data.role === 'cashier') {
                            location.href = "{{route('cashier.index')}}"
                        } else if (response.data.role === 'offline_storage') {
                            location.href = "{{route('stages.create')}}"
                        } else if (response.data.role === 'online_storage') {
                            location.href = "{{route('storages.ecommerce')}}"
                        } else {
                            alert('Sorry, we could not find entered account.');
                        }
                    }
                }).catch(function(error) {
                    if (!error.response.data.status) {
                        $('#email').addClass('error')
                        $('#email').addClass('animate__animated animate__headShake')

                        $('#password').addClass('error')
                        $('#password').addClass('animate__animated animate__headShake')

                    }
                }).finally(function() {
                    $('#login').text('Masuk');
                    setTimeout(() => {
                        $('#email').removeClass('error')
                        $('#email').removeClass('animate__animated animate__headShake')

                        $('#password').removeClass('error')
                        $('#password').removeClass('animate__animated animate__headShake')
                    }, 3500);
                })
            })
        })
    </script>
</body>

</html>