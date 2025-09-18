<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
    <title>@lang('Forget Password') | {{ $ApplicationSetting->item_name }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}" />
    <!-- Ambitious CSS -->
    <link href="{{ asset('assets/css/frontend.css') }}" rel="stylesheet">
    @if(session('locale') == 'ar')
        <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/plugins/alertifyjs/css/themes/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <style>
        .invalid-feedback {
            position: absolute;
            top: 40px;
            font-weight: bold;
        }
        .bg-custom {
            background: #f3f3f3;
            border: 1px solid #d9d1d1;
        }
        .parsley-errors-list {
            list-style-type: none;
            padding: 0;
            margin: 5px 0 0;
            color: #ff4d4d;
            font-size: 0.875rem;
            position: absolute;
            top: 35px;
            display: block;
            width: 100%;
        }
        .parsley-errors-list li:before {
            content: "⚠ ";
            margin-right: 5px;
            font-size: 1rem;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-info">
            <div class="card-header bg-info text-center">
                <a class="h1"><span class="identColor"><b>{{ $ApplicationSetting->item_name }}</b></span></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg m-0 p-0">Enter your registered email to reset password</p>
                <br/>
                <form method="POST" action="{{ route('forgot-password.email') }}" data-parsley-validate>
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               placeholder="@lang('Email')"
                               required
                               data-parsley-required="true"
                               data-parsley-type="email"
                               data-parsley-required-message="@lang('Please enter valid email')"
                               data-parsley-trigger="focusout">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
            
                    <!-- Display Success or Error Messages Here -->
                    @if (session('status'))
                        <div class="mb-2 text-success font-weight-bold text-center">
                            {{ session('status') }}
                        </div>
                    @elseif ($errors->any())
                        <div class="mb-2 text-danger font-weight-bold text-center">
                            {{ $errors->first() }}
                        </div>
                    @endif
            
                    <div class="social-auth-links text-center mt-4 mb-3">
                        <button type="submit" class="btn btn-block btn-info" id="reset-password-button">
                            <i class="fas fa-paper-plane mr-2"></i> @lang('Reset Password')
                        </button>
                    </div>
                    
                </form>
                <p class="mb-0 text-center">
                    <a href="{{ route('login') }}" class="text-center">@lang('Back to Login')</a>
                </p>
            </div>
            
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    @if(session('locale') == 'ar')
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @else
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    @endif
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
    <!-- validation -->
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <script>
        // document.querySelector('form').addEventListener('submit', function (e) {
        //     const resetButton = document.getElementById('reset-password-button');
        //     const spinner = document.getElementById('button-spinner');
    
        //     // Disable the button and show the spinner
        //     resetButton.disabled = true;
        //     spinner.classList.remove('d-none');
        // });
        $('#reset-password-button').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission

            var $this = $(this);
            var $form = $this.closest('form');

            // Trigger validation
            if ($form.parsley().validate()) {
                // Disable the button to prevent multiple clicks
                $this.prop('disabled', true);
                $this.html('<i class="fas fa-spinner fa-spin mr-2"></i> Sending Mail...');

                // Submit the form after validation passes
                $form.submit();
            } else {
                // If validation fails, do nothing
                console.log('Validation failed. Spinner not triggered.');
            }
        });
    </script>
    
</body>
</html>
