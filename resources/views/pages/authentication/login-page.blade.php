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

</head>


<body id="page-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card p-4 border-0 shadow-sm" style="margin-top: 30%">
                    <img src="{{ asset('images/default.svg') }}" class="mx-auto d-block" alt="" style="width: 50%;">
                    <!-- <hr> -->
                    <form action="{{ route('process.login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control rounded-pill" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control rounded-pill" required>
                        </div>
                        
                        <input type="submit" class="btn btn-sm rounded-pill btn-primary mb-3 pr-3 pl-3" value="Masuk">
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
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
