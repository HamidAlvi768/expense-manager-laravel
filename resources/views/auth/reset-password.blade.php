<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
        <title>@lang('Reset Password') | {{ $ApplicationSetting->item_name }}</title>

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
        <style>
            .invalid-feedback {
                position: absolute;
                top: 40px;
                font-weight: bold;
            }

            .input-group-text.eye-icon {
                cursor: pointer;
                padding: 0.375rem;
                background-color: transparent;
                border: none;
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0.6;
            }

            .parsley-errors-list {
                list-style-type: none;
                padding: 0;
                margin: 5px 0 0;
                color: #ff4d4d;
                font-size: 0.875rem;
                position: absolute;
                top: 35px;
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
                    <p class="login-box-msg m-0 p-0">@lang('Enter your new password')</p>
                    <br/>
                    <form method="POST" action="{{ route('reset-password.update') }}" data-parsley-validate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="input-group mb-3">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   placeholder="@lang('New Password')"
                                   required
                                   data-parsley-required="true"
                                   data-parsley-minlength="8"
                                   data-parsley-required-message="@lang('Please enter a new password')"
                                   data-parsley-minlength-message="@lang('Password must be at least 8 characters long')"
                                   data-parsley-trigger="focusout">
                            <div class="input-group-append">
                                <div class="input-group-text eye-icon" id="togglePassword">
                                    <span class="fas fa-eye" id="eyeIcon"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input id="password_confirmation" type="password"
                                   class="form-control"
                                   name="password_confirmation"
                                   placeholder="@lang('Confirm Password')"
                                   required
                                   data-parsley-required="true"
                                   data-parsley-equalto="#password"
                                   data-parsley-required-message="@lang('Please confirm your password')"
                                   data-parsley-equalto-message="@lang('Passwords do not match')"
                                   data-parsley-trigger="focusout">
                            <div class="input-group-append">
                                <div class="input-group-text eye-icon" id="togglePasswordConfirm">
                                    <span class="fas fa-eye" id="eyeIconConfirm"></span>
                                </div>
                            </div>
                        </div>

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
                                <i class="fas fa-key mr-2"></i> @lang('Reset Password')
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
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
        <!-- validation -->
        <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>

        <script>
            $(document).ready(function () {
                // Toggle password visibility for New Password
                $('#togglePassword').on('click', function () {
                    const passwordField = $('#password');
                    const eyeIcon = $('#eyeIcon');
        
                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
        
                // Toggle password visibility for Confirm Password
                $('#togglePasswordConfirm').on('click', function () {
                    const passwordConfirmField = $('#password_confirmation');
                    const eyeIconConfirm = $('#eyeIconConfirm');
        
                    if (passwordConfirmField.attr('type') === 'password') {
                        passwordConfirmField.attr('type', 'text');
                        eyeIconConfirm.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordConfirmField.attr('type', 'password');
                        eyeIconConfirm.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            });
        </script>
        
    </body>
</html>
