<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Recover Password | ZielTech Academy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('student/assets/images/favicon.ico') }}">
    <!-- Theme Config Js -->
    <script src="{{asset('student/assets/js/hyper-config.js') }}"></script>
    <!-- Vendor css -->
    <link href="{{asset('student/assets/css/vendor.min.css' ) }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{asset('student/assets/css/app-saas.min.css' ) }}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="{{asset('student/assets/css/icons.min.css' ) }}" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg pb-0">
    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="card-body d-flex flex-column h-100 gap-3">
                <!-- Logo -->
                <div class="auth-brand text-center text-lg-start">
                    <a href="#" class="logo-dark">
                        <span> <img src="{{asset('student/assets/images/logo-dark.png' ) }}" alt="dark logo" height="60"></span>
                    </a>
                    <a href="#" class="logo-light">
                        <span><img src="{{asset('student/assets/images/logo.png' ) }}" alt="logo" height="60"></span>
                    </a>
                </div>
                <div class="my-auto">
                    <!-- title-->
                    <h4 style="color: var(--Black-Pearl-950, #001021);">Reset Password</h4>
                    <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to
                        reset your password.</p>
                    <!-- form -->
                    <form action="#">
                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">Email address</label>
                            <input class="form-control" type="email" id="emailaddress" required=""
                                placeholder="Enter your email">
                        </div>
                        <div class="mb-0 text-center d-grid">
                            <button class="btn btn text-white" style="background: #195BAC;" type="submit"><i
                                    class="mdi mdi-lock-reset"></i> Reset Password </button>
                        </div>
                    </form>
                    <!-- end form-->
                </div>
                <!-- Footer-->
                <footer class="footer footer-alt">
                    <p class="text-muted">Back to <a href="{{ route('student.login.page') }}" class="text-muted ms-1"
                            style="color: var(--Black-Pearl-800, #004D95) !important;font-weight: 600;"><b>Log
                                In</b></a></p>
                </footer>
            </div> <!-- end .card-body -->
        </div>
        <!-- end auth-fluid-form-box-->
    </div>
    <!-- end auth-fluid-->
    <!-- Vendor js -->
    <script src="{{asset('student/assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{asset('student/assets/js/app.min.js') }}"></script>

</body>


<!-- Mirrored from coderthemes.com/hyper/saas/pages-recoverpw-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Mar 2025 05:36:06 GMT -->

</html>