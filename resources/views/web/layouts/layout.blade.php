<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Zieltech Academy | Transforming Education with Innovative Tech</title>
    <meta name="description" content="Zieltech Academy transforms education with innovative tech initiatives, enhancing learning through technology-driven solutions."/>
    <meta name="keywords" content="Zieltech Academy, tech education, digital learning, technology-driven solutions, innovative learning">
    <!-- bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- font awersome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- testimonial slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <!-- aos link -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel='stylesheet' href="{{ asset('web/plugins/goodlayers-core/plugins/combine/style.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' href="{{ asset('web/plugins/goodlayers-core/include/css/page-builder.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' href="{{ asset('web/plugins/revslider/public/assets/css/settings.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' href="{{ asset('web/css/style-core.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' href="{{ asset('web/css/kingster-style-custom.css') }}" type='text/css' media='all' />
    <!-- style link -->
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('web/css/campus.css') }}" />
    <link rel="stylesheet" href="{{ asset('web/css/about.css') }}" />
    <link rel="stylesheet" href="{{ asset('web/css/branches.css') }}" />
    <link rel="stylesheet" href="{{ asset('web/css/index.css') }}" />
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700%2C400" rel="stylesheet" property="stylesheet" type="text/css" media="all">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2Cregular%2Citalic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CABeeZee%3Aregular%2Citalic&amp;subset=latin%2Clatin-ext%2Cdevanagari&amp;ver=5.0.3' type='text/css' media='all' />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('web/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('web/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('web/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('web/site.webmanifest') }}">
    @yield('online-meta')
    @yield('offline-meta')
    @yield('terms-and-conditions-meta')
    @yield('privacy-and-policy-meta')
</head>

<body>
    <!-- navbar section -->
    <header>
        <div class="logo">
            <a href="{{ route('home') }}"><img class="" src="{{ asset('web/upload/Ziel-Logo.webp') }}" alt=""></a>
        </div>
        <nav class="nav" id="nav-menu">
            <ion-icon name="close" class="header__close" id="close-menu"></ion-icon>
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{ route('home') }}" class="nav__link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('about') }}" class="nav__link {{ request()->routeIs('about') ? 'active' : '' }}">About us</a>
                </li>
                {{-- <li class="nav__item">
                    <a href="{{ route('online-courses') }}" class="nav__link {{ request()->routeIs('online-courses') ? 'active' : '' }}">Online Courses</a>
                </li> --}}
                <li class="nav__item">
                    <a href="{{ route('placement') }}" class="nav__link {{ request()->routeIs('placement') ? 'active' : '' }}">Placements</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('branch') }}" class="nav__link {{ request()->routeIs('branch') ? 'active' : '' }}">Branches</a>
                </li>
                <li class="nav__item">
                    <a href="{{ route('contact') }}" class="nav__link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact us</a>
                </li>
            </ul>
            <a href="#register-section" style="color: white;"><button>Enroll</button></a>
        </nav>
        <ion-icon name="menu" class="header__toggle" id="toggle-menu"></ion-icon>
    </header>

    @yield('index')
    @yield('about')
    @yield('placement')
    @yield('campus')
    @yield('branches')
    @yield('contact')
    @yield('terms-and-conditions')
    @yield('privacy-and-policy')

    <!-- footer section -->
    <footer style="margin-top: 0px !important;" >
        <div class="kingster-footer-wrapper ">
            <div class="kingster-footer-container kingster-container clearfix">
                <div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
                    <div id="text-2" class="widget widget_text kingster-widget">
                        <div class="textwidget">
                            <img class="img-fluid footer-logo" src="{{ asset('web/upload/footer-logo-ziel.webp') }}" alt="" />
                            <p>Zieltech Academy provides hands-on learning for career-focused technical education.</p>
                        </div>
                    </div>
                </div>
                <div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
                    <div id="gdlr-core-custom-menu-widget-3" class="widget widget_gdlr-core-custom-menu-widget kingster-widget">
                        <h3 class="kingster-widget-title">Quick Links</h3><span class="clear"></span>
                        <div class="menu-campus-life-container">
                            <ul id="menu-campus-life" class="gdlr-core-custom-menu-widget gdlr-core-menu-style-plain">
                                <li class="menu-item"><a href="{{ route('home') }}">Home</a></li>  
                                <li class="menu-item"><a href="{{ route('about') }}">About</a></li>
                                {{-- <li class="menu-item"><a href="{{ route('online-courses') }}">Online Courses</a></li> --}}
                                <li class="menu-item"><a href="{{ route('placement') }}">Placements</a></li>
                                <li class="menu-item"><a href="{{ route('branch') }}">Branches</a></li>
                                <li class="menu-item"><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
                    <div id="gdlr-core-custom-menu-widget-2" class="widget widget_gdlr-core-custom-menu-widget kingster-widget">
                        <h3 class="kingster-widget-title">Courses and Learning</h3><span class="clear"></span>
                        <div class="menu-our-campus-container">
                            <ul id="menu-our-campus" class="gdlr-core-custom-menu-widget gdlr-core-menu-style-plain">
                            {{-- <li class="menu-item">
                                <a href="#programs-section">Offline Courses</a>
                            </li> --}}
                            <li class="menu-item"><a href="{{ route('home') }}">Online Courses</a></li>
                            <li class="menu-item">
                                <a href="{{ route('placement') }}">Career Placement</a>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="kingster-footer-column kingster-item-pdlr kingster-column-15">
                <div id="gdlr-core-custom-menu-widget-4" class="widget widget_gdlr-core-custom-menu-widget kingster-widget">
                    <h3 class="kingster-widget-title" style="color: #fffff">Contact Us</h3><span class="clear"></span>
                    <div class="menu-academics-container">
                        <ul id="menu-academics" class="gdlr-core-custom-menu-widget gdlr-core-menu-style-plain">
                            <!-- Removed the previous menu items -->
                        </ul>
                    </div>
                    <div class="ziel-app-download">
                    @if ($contact)
                        <p>
                            <i class="fas fa-map-marker-alt"></i> {{ $contact->address }}
                        </p>
                        <p>
                            <a href="tel:{{ $contact->phone }}" class="contact-link">
                                <i class="fas fa-phone"></i> {{ $contact->phone }}
                            </a>
                        </p>
                        <p>
                            <a href="mailto:{{ $contact->email }}" class="contact-link">
                                <i class="fas fa-envelope"></i> {{ $contact->email }}
                            </a>
                        </p>
                    @endif
                        <div class="gdlr-core-social-network-item gdlr-core-item-pdb gdlr-core-none-align" style="padding-bottom: 0px;">
                            @foreach ($socialLinks as $link)
                                @if ($link['platform'] !== 'twitter')
                                    <a href="{{ $link['url'] }}" target="_blank" class="gdlr-core-social-network-icon" title="{{ ucfirst($link['platform']) }}">
                                        <i class="fa fa-{{ $link['platform'] == 'x' ? 'xmark' : ($link['platform'] == 'youtube' ? 'youtube-play' : $link['platform']) }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        <!-- <div class="download-buttons" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                            <a href="#" target="_blank"><i class="fab fa-google-play fa-2x"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-apple fa-2x"></i></a>
                            <button class="btn btn-primary">Download Now</button>
                        </div> -->
                    </div>
                </div>
                </div>
            </div>
        </div>
        
        <div class="kingster-copyright-wrapper">
            <div class="kingster-copyright-container kingster-container clearfix">
                <div class="kingster-copyright-left kingster-item-pdlr">© 2025  ZIELTECH ACADEMY PVT All rights reserved.</div>
                 <div class="kingster-copyright-right kingster-item-pdlr">
                    <div class="gdlr-core-social-network-item gdlr-core-item-pdb  gdlr-core-none-align" style="padding-bottom: 0px ;">
                        <a href="{{ route('terms-and-conditions') }}" class="gdlr-core-social-network-icon footer-link" style="color:#000 !important; font-size: 16px !important;" >
                            Terms & Conditions
                        </a>
                        <a href="{{ route('privacy-and-policy') }}" class="gdlr-core-social-network-icon footer-link" style="color:#000 !important; font-size: 16px !important;">
                            Privacy Policy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- bootstrap link script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- navbar script js -->
    <script src="{{ asset('web/js/script.js') }}"></script>
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <!-- navbar script js end -->
    <!-- testimonial script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script>
    <script>
        document.querySelectorAll('a[href="#register-section"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const targetSection = document.getElementById('register-section');
            
            // If the section exists on this page, scroll smoothly
            if (targetSection) {
                e.preventDefault(); // Prevent default anchor jump
                targetSection.scrollIntoView({ behavior: 'smooth' });
                history.pushState(null, null, '#register-section'); // Update URL
            }
            // If section doesn't exist, redirect to the correct page with the hash
            else {
                e.preventDefault(); // Prevent default behavior
                window.location.href = "/#register-section"; // Redirect to homepage (adjust if needed)
            }
        });
    });
    </script>
    <script>
        $(document).ready(function () {
            var silder = $(".owl-carousel");
            silder.owlCarousel({
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: false,
                items: 1,
                stagePadding: 10,
                center: true,
                nav: false,
                margin: 10,
                dots: true,
                loop: true,
                responsive: {
                    0: { items: 1 },
                    480: { items: 1 },
                    575: { items: 1 },
                    768: { items: 2 },
                    991: { items: 2 },
                    1200: { items: 3 }
                }
            });
        });
    </script>
    <!-- aos link -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
            once: true,
        })
    </script>
    <script src="{{ asset('web/script.js') }}"></script>

<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
<!-- navbar script js end -->
<script type='text/javascript' src="{{  asset('web/plugins/goodlayers-core/plugins/combine/script.js') }}"></script>
<script type='text/javascript'>
    var gdlr_core_pbf = {
        "admin": "",
        "video": {
            "width": "640",
            "height": "360"
        },
        "ajax_url": "#"
    };
</script>
<script type='text/javascript' src="{{  asset('web/plugins/goodlayers-core/include/js/page-builder.js') }}"></script>
<script type='text/javascript' src="{{ asset('web/js/jquery/ui/effect.min.js') }}"></script>
<script type='text/javascript'>
    var kingster_script_core = {
        "home_url": "index.html"
    };
</script>
<script type='text/javascript' src="{{ asset('web/js/plugins.min.js') }}"></script>
<script type='text/javascript' src="{{ asset('web/js/isotope.js') }}"></script>
<script type="text/javascript" src="{{  asset('web/plugins/quform/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{  asset('web/plugins/quform/js/scripts.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modulesMenu = document.querySelector('.menu-item a[href*="modulesSection"]');
        if (modulesMenu) {
            modulesMenu.addEventListener("click", function (event) {
                event.preventDefault();

                // Check if the section exists on the page
                const modulesSection = document.getElementById("modulesSection");

                if (modulesSection) {
                    modulesSection.scrollIntoView({ behavior: "smooth" });
                } else {
                    // Redirect to homepage and append hash
                    window.location.href = "/#modulesSection";
                }
            });
        }
    });
</script>
<script>
    document.querySelectorAll('a[href="#course-modules"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetSection = document.getElementById('course-modules');
            
            if (targetSection) {
                // Scroll if section exists
                e.preventDefault();
                targetSection.scrollIntoView({ behavior: 'smooth' });
                history.pushState(null, null, '#course-modules');
            } else {
                // Optional: Redirect to a specific URL (not necessarily homepage)
                window.location.href = "/campus#course-modules"; // Custom URL
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        var validator = $("#EnrollmentForm").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                course_id: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    required: "Name is required",
                    minlength: "Name must be at least 3 characters"
                },
                email: {
                    required: "Email is required",
                    email: "Enter a valid email address"
                },
                phone: {
                    required: "Phone number is required",
                    digits: "Only numbers allowed",
                    minlength: "Must be at least 10 digits",
                    maxlength: "Cannot exceed 15 digits"
                },
                course_id: {
                    required: "Please select a course"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#EnrollmentForm button[type='submit']").click(function (event) {
            if (!$("#EnrollmentForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
        });
    });
</script>
</body>

</html>