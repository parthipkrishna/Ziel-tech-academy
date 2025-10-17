<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In  </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('web/zielfavicon.png') }}">

        <!-- Theme Config Js -->
        <script src="{{asset('dashboard/assets/js/hyper-config.js')}}"></script>

        <!-- App css -->
        <link href="{{asset('dashboard/assets/css/app-saas.min.css') }}" rel="stylesheet"  type="text/css" id="app-style" />

        <!-- Icons css -->
        <link href="{{asset('dashboard/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body class="authentication-bg pb-0">
        <div id="preloader">
            <div id="status">
                <div class="bouncing-loader">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="auth-fluid">
            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">
                <div class="card-body d-flex flex-column ">
                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-start " style="margin-bottom: 0;">
                        <a href="#" class="logo-dark">
                            <span><img src="{{ asset('dashboard/logo/ziel-logo.webp') }}" alt="dark logo" height="22" class="mb-4"></span>
                        </a>
                        <a href="#" class="logo-light">
                            <span><img src="{{ asset('dashboard/logo/ziel-logo.webp') }}" alt="logo"  style="width: auto; height: 77px;"></span>
                        </a>
                    </div>
                    <div class="">
                        <!-- title-<div class="my-auto">-->
                        <h4 class="mt-0">Sign In</h4>
                        <div class="row justify-content-center">
                            {{-- Display general messages --}}
                            @if (session()->has('message'))
                                <div class="alert alert-danger text-center w-75">
                                    <h6 class="text-center fw-bold">{{ session('message') }}</h6>
                                </div>
                            @endif

                            {{-- Display validation error messages --}}
                            @if ($errors->any())
                                <div class="alert alert-danger text-center w-75">
                                    @foreach ($errors->all() as $error)
                                    <h6 class="text-center fw-bold">{{ $error }}</h6>
                                @endforeach
                                </div>
                            @endif
                        </div>
                        <p class="text-muted mb-4">Enter your email address and password to access account.</p>
                        <!-- form -->
                        <form action="{{ route('admin.login') }}" method="POST" id="adminLoginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Email address</label>
                                <input class="form-control" type="email" id="emailaddress" name="email" 
                                    value="{{ old('email', request()->cookie('email')) }}" 
                                    placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <!-- <a href="#" class="text-muted float-end">
                                    <small>Forgot your password?</small>
                                </a> -->
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input class="form-control" type="password" id="password" name="password" 
                                        value="{{ old('password', request()->cookie('password')) }}" 
                                        placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" 
                                        {{ request()->cookie('email') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>
                            <div class="d-grid mb-0 text-center">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-login"></i>Log In </button>
                                <div id="loginError" class="mt-2"></div>
                            </div>
                        </form>
                        <!-- end form-->
                    </div>
                    <!-- Footer-->
                    <footer class="footer footer-alt">
                    </footer>
                </div> <!-- end .card-body -->
            </div> <!-- end auth-fluid-form-box-->
            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <h2 class="mb-3">I love the color!</h2>
                    <p class="lead"><i class="mdi mdi-format-quote-open"></i> It's a elegent templete. I love it very much! . <i class="mdi mdi-format-quote-close"></i>
                    </p>
                    <p>
                        - Hyper Admin User
                    </p>
                </div> <!-- end auth-user-testimonial-->
            </div> <!-- end Auth fluid right content -->
        </div> <!-- end auth-fluid-->

        <!-- Vendor js -->
        <script src="{{asset('dashboard/assets/js/vendor.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('dashboard/assets/js/app.min.js')}}"></script>

        {{-- validation --}}
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
            </script>

    </body>
</html>
