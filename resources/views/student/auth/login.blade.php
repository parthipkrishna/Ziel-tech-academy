<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('web/favicon-32x32.png') }}">

    <!-- Theme Config Js -->
    <script src="{{asset('student/assets/js/hyper-config.js')}}"></script>

    <!-- Vendor css -->
    <link href="{{asset('student/assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
   <link href="{{asset('student/assets/css/app-saas.min.css') }}" rel="stylesheet"  type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{asset('student/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="authentication-bg pb-0">

    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="card-body d-flex flex-column h-100 gap-3">

                <!-- Logo -->
                <div class="auth-brand text-center text-lg-start">
                    <a href="index.html" class="logo-dark">
                        <span><img src="{{asset('student/assets/images/logo-dark.png' ) }}" alt="dark logo" height="60"></span>
                    </a>
                    <a href="index.html" class="logo-light">
                        <span><img src="{{asset('student/assets/images/logo.png' ) }}" alt="logo" height="60"></span>
                    </a>
                </div>

                <div class="my-auto">
                    <!-- title-->
                    <h4 class="mt-0" style="color: var(--Black-Pearl-950, #001021);">Log in</h4>
                    <p class="text-muted mb-4">Enter your email address and password</p>

                    <!-- form -->
                    <form action="{{ route('student.login') }}" method="post" id="adminLoginForm">
                        @csrf
                        <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control" type="email" id="emailaddress" name="email" 
                                        value="{{ old('email', request()->cookie('email')) }}" 
                                        placeholder="Enter your email" required>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <label for="password" class="form-label">Password</label>
                                        <a href="{{ route('student.reset.password') }}" class="text-muted small">Forgot your password?</a>
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="password" name="password" 
                                            value="{{ old('password', request()->cookie('password')) }}" 
                                            placeholder="Enter your password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" 
                                        {{ request()->cookie('email') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                </div>

                                <div class="d-grid mb-3">
                                    <button id="loginBtn" class="btn btn-primary" type="submit"><i class="mdi mdi-login"></i> Log In
                                        <span id="loginSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                                    </button>
                                    <div id="loginError" class="mt-2"></div>
                                </div>
                        <!-- social-->
                        <div class="text-center mt-4">
                            <p class="text-muted font-16"
                                style="color: var(--Color-text, #474747) !important;font-weight: 500;">Login with</p>
                            <ul class="social-list list-inline mt-3">
                                <li class="list-inline-item">
                                    <a href="#" class=""><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                            viewBox="0 0 128 128">
                                            <path
                                                d="M97.905 67.885c.174 18.8 16.494 25.057 16.674 25.137c-.138.44-2.607 8.916-8.597 17.669c-5.178 7.568-10.553 15.108-19.018 15.266c-8.318.152-10.993-4.934-20.504-4.934c-9.508 0-12.479 4.776-20.354 5.086c-8.172.31-14.395-8.185-19.616-15.724C15.822 94.961 7.669 66.8 18.616 47.791c5.438-9.44 15.158-15.417 25.707-15.571c8.024-.153 15.598 5.398 20.503 5.398c4.902 0 14.106-6.676 23.782-5.696c4.051.169 15.421 1.636 22.722 12.324c-.587.365-13.566 7.921-13.425 23.639M82.272 21.719c4.338-5.251 7.258-12.563 6.462-19.836c-6.254.251-13.816 4.167-18.301 9.416c-4.02 4.647-7.54 12.087-6.591 19.216c6.971.54 14.091-3.542 18.43-8.796" />
                                        </svg></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class=""><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                            viewBox="0 0 48 48">
                                            <path fill="#ffc107"
                                                d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917" />
                                            <path fill="#ff3d00"
                                                d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691" />
                                            <path fill="#4caf50"
                                                d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.9 11.9 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44" />
                                            <path fill="#1976d2"
                                                d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917" />
                                        </svg></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class=""><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                            viewBox="0 0 256 256">
                                            <path fill="#1877f2"
                                                d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445" />
                                            <path fill="#fff"
                                                d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z" />
                                        </svg></a>
                                </li>
                            </ul>
                        </div>
                    </form>
                    <!-- end form-->
                </div>

                <!-- Footer-->
                <footer class="footer footer-alt">
                    <!-- <p class="text-muted">New to zieltech? <a href="pages-register-2.html" class="text-muted ms-1" style="color: var(--Black-Pearl-800, #004D95) !important;font-weight: 600;"><b>Register now</b></a></p> -->
                </footer>

            </div> <!-- end .card-body -->
        </div>
        <!-- end auth-fluid-form-box-->
    </div>
    <!-- end auth-fluid-->
    <!-- Vendor js -->
    <script src={{asset('student/assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src={{asset('student/assets/js/app.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script>
            $(document).ready(function () {
                var validator = $("#adminLoginForm").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 6
                        }
                    },
                    messages: {
                        email: {
                            required: "Email is required",
                            email: "Enter a valid email address"
                        },
                        password: {
                            required: "Password is required",
                            minlength: "Password must be at least 6 characters"
                        }
                    },
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.addClass("text-danger").insertAfter(element.parent());
                        } else {
                            error.addClass("text-danger").insertAfter(element);
                        }
                    },
                    highlight: function(element) {
                        $(element).addClass("is-invalid").removeClass("is-valid");
                    },
                    unhighlight: function(element) {
                        $(element).removeClass("is-invalid").addClass("is-valid");
                    },
                    onkeyup: function(element) {
                        $(element).valid(); // Validate while typing
                    },
                    onfocusout: function(element) {
                        $(element).valid(); // Validate when moving out of the field
                    }
                });

                // ✅ Show error messages immediately when clicking submit if fields are empty
                $("#loginBtn").click(function (event) {
                    if (!$("#adminLoginForm").valid()) {
                        validator.focusInvalid(); // Move focus to first invalid field
                        event.preventDefault(); // Prevent form submission if invalid
                    }
                });
            });
            </script>
            <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
            <script>
                document.getElementById('togglePassword').addEventListener('click', function () {
                    let passwordField = document.getElementById('password');
                    let icon = this.querySelector('i');

                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordField.type = "password";
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            </script>
            <script>
                $('#adminLoginForm').on('submit', function(e) {
                    e.preventDefault();

                    $('#loginError').html(''); // Clear previous errors

                    let formData = $(this).serialize();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        },
                        success: function(response) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                // Validation errors
                                const errors = xhr.responseJSON.errors;
                                let message = '';
                                $.each(errors, function(key, value) {
                                    message += `<div class="text-danger">${value[0]}</div>`;
                                });
                                $('#loginError').html(message);
                            } else if (xhr.responseJSON?.message) {
                                $('#loginError').html(`<div class="text-danger">${xhr.responseJSON.message}</div>`);
                            } else {
                                $('#loginError').html('<div class="text-danger">Something went wrong. Please try again.</div>');
                            }
                        }
                    });
                });
                $('#adminLoginForm').on('submit', function(e) {
                    e.preventDefault();
                    const btn = $('#loginBtn');
                    const spinner = $('#loginSpinner');
                    const errorDiv = $('#loginError');

                    spinner.removeClass("d-none");
                    btn.prop("disabled", true);

                    errorDiv.html(''); // Clear previous errors
                    let formData = $(this).serialize();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        },
                        success: function(response) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                spinner.addClass("d-none");
                                btn.prop("disabled", false);
                            }
                        },
                        error: function(xhr) {
                            let message = '';

                            if (xhr.status === 419) {
                                // Ignore or optionally reload page silently if needed
                                console.log('Session expired, but ignored.');
                            } else if (xhr.status === 422) {
                                // Validation errors
                                const errors = xhr.responseJSON.errors;
                                message = '';
                                $.each(errors, function(key, value) {
                                    message += `<div class="text-danger">${value[0]}</div>`;
                                });
                            } else if (xhr.responseJSON?.message) {
                                message = `<div class="text-danger">${xhr.responseJSON.message}</div>`;
                            } else {
                                message = '<div class="text-danger">Something went wrong. Please try again.</div>';
                            }

                            if (message) {
                                errorDiv.html(message);
                            }
                        },
                        complete: function() {
                            btn.prop("disabled", false);
                            spinner.addClass("d-none");
                        }
                    });
                });

            </script>

</body>
<!-- Mirrored from coderthemes.com/hyper/saas/pages-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Mar 2025 05:36:06 GMT -->
</html>