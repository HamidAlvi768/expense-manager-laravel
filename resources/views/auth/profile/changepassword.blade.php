@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Change Password') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3>{{ __('Change Password') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('profile.updatePassword') }}" method="POST" enctype="multipart/form-data" id="change-password-form">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('Current Password') }} <b class="ambitious-crimson"></b></h4>
                            </label>
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading" name="current-password" id="current-password" type="password" placeholder="{{ __('Type Your Current Password Here') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text eye-icon" id="toggleCurrentPassword">
                                            <span class="fas fa-eye" id="eyeCurrentIcon"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('current-password'))
                                {{ Session::flash('error', $errors->first('current-password')) }}
                            @endif
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('New Password') }} <b class="ambitious-crimson"></b></h4>
                            </label>
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading" name="new-password" id="new-password" type="password" placeholder="{{ __('Type Your New Password Here') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text eye-icon" id="toggleNewPassword">
                                            <span class="fas fa-eye" id="eyeNewIcon"></span>
                                        </div>
                                    </div>
                                </div>
                                <small id="name" class="form-text text-muted">{{ __('6 Characters Long') }}</small>
                            </div>
                            @if ($errors->has('new-password'))
                                {{ Session::flash('error', $errors->first('new-password')) }}
                            @endif
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 ambitious-center">
                                <h4>{{ __('Confirm Password') }} <b class="ambitious-crimson"></b></h4>
                            </label>
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <input class="form-control ambitious-form-loading" name="new-password_confirmation" id="new-password-confirm" type="password" placeholder="{{ __('Type Your Confirm Password Here') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text eye-icon" id="toggleConfirmPassword">
                                            <span class="fas fa-eye" id="eyeConfirmIcon"></span>
                                        </div>
                                    </div>
                                </div>
                                <small id="name" class="form-text text-muted">{{ __('6 Characters Long') }}</small>
                                <span id="passwordMismatchError" class="text-danger" style="display: none;">{{ __('Passwords do not match!') }}</span>
                            </div>
                            @if ($errors->has('new-password_confirmation'))
                                {{ Session::flash('error', $errors->first('new-password_confirmation')) }}
                            @endif
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
<<<<<<< HEAD
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-md" id="submit-btn" disabled/>
=======
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-lg" id="submit-btn" disabled/>
>>>>>>> 59200bb (Initial commit with expense manager code)
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Toggle JS -->
    <script>
        // Toggle current password visibility
        document.getElementById('toggleCurrentPassword').addEventListener('click', function () {
            const passwordField = document.getElementById('current-password');
            const eyeIcon = document.getElementById('eyeCurrentIcon');

            // Toggle password visibility
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Toggle new password visibility
        document.getElementById('toggleNewPassword').addEventListener('click', function () {
            const passwordField = document.getElementById('new-password');
            const eyeIcon = document.getElementById('eyeNewIcon');

            // Toggle password visibility
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const passwordField = document.getElementById('new-password-confirm');
            const eyeIcon = document.getElementById('eyeConfirmIcon');

            // Toggle password visibility
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Real-time password mismatch validation
        document.getElementById('new-password').addEventListener('input', checkPasswordMatch);
        document.getElementById('new-password-confirm').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('new-password-confirm').value;
            const errorElement = document.getElementById('passwordMismatchError');
            const submitButton = document.getElementById('submit-btn');

            // Show error if passwords don't match
            if (newPassword !== confirmPassword && confirmPassword !== '') {
                errorElement.style.display = 'block';
                submitButton.disabled = true; // Disable submit button
            } else {
                errorElement.style.display = 'none';
                submitButton.disabled = false; // Enable submit button
            }
        }
    </script>
@endsection
