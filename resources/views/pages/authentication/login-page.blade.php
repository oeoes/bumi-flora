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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .error {
            border-color: #dc3545
        }

    </style>

</head>


<body id="page-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card p-4 border-0 shadow-sm animate__animated animate__flipInX" style="margin-top: 30%">
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
                        </div>

                        <button id="login" class="btn btn-sm rounded-pill btn-primary mb-3 pr-3 pl-3">Masuk</button>
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
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/axios.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('submit', '#form-login', function (e) {
                e.preventDefault()
            })

            // login
            $(document).on('click', '#login', function () {
                axios.post('/login', {
                    email: $('#email').val(),
                    password: $('#password').val()
                }).then(function (response) {
                    if (response.data.status) {
                        if(response.data.role == 'user'){
                            location.href="{{route('cashier.index')}}"
                        } else {
                            location.href="{{route('dashboard.index')}}"
                        }                            
                    }
                }).catch(function (error) {
                    if (!error.response.data.status) {
                        $('#email').addClass('error')
                        $('#email').addClass('animate__animated animate__headShake')

                        $('#password').addClass('error')
                        $('#password').addClass('animate__animated animate__headShake')

                    }
                }).finally(function () {
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
