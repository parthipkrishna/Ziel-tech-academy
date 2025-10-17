@extends('web.layouts.layout')
@section('branches')
    <!-- banner section -->
    @if(count($banner) > 0)
    @foreach ($banner as $item)
        <section class="banner-sections" 
            style="background-image: url('{{ $item->image_url ? Storage::url($item->image_url) : asset('web/upload/Branch_bannernew.webp') }}');">
            <div class="container mt-4">
                <div class="row" data-aos="fade-up">
                    <h1>{{ $item->title }}</h1>
                    <h6>{{ $item->short_desc }}</h6>
                </div>
            </div>
        </section>
    @endforeach
    @else
        <section class="banner-sections" style="background-image: url('{{ asset('web/upload/Branch_bannernew.webp') }}');">
            <div class="container mt-4">
                <div class="row" data-aos="fade-up">
                    <h1>Branches</h1>
                    <!-- <h6>Get In Touch</h6> -->
                </div>
            </div>
        </section>
    @endif
    <!-- branch network section -->
    <section class="branchNetwork-section" data-aos="fade-up">
    <div class="container">
        <div class="row" data-aos="fade-up">
            <div class="col-12 text-start">
                <h2>Our Branches</h2>
                <p class="text-start">Discover our conveniently located branches, offering quality services and support. We ensure easy access to excellence, bringing our expertise closer to you for a smooth and enriching experience.</p>
            </div>
        </div>
    </div>
        <div class="container">
    @foreach ($branches as $branch)
    <div class="row map-div">
        <!-- Branch Image with Border Radius -->
        <div class="col-lg-4 col-md-6 mt-5">
            <div style="width: 100%; height: 370px; overflow: hidden; border-radius: 20px;">
                {!! str_replace(['width="600"', 'height="450"'], ['width="100%"', 'height="400px"'], $branch->google_map_link) !!}
            </div>
        </div>
        <!-- Branch Details -->
            <div class="col-lg-8 col-md-6 mt-5">
                <div class="map-icons">
                <a href="https://www.facebook.com/profile.php?id=61574238955271" target="_blank" class="gdlr-core-social-network-icon" title="Facebook">
                    <i class="fa fa-facebook"></i>
                </a>
                <a href="https://www.linkedin.com/company/zieltech-academy/" target="_blank" class="gdlr-core-social-network-icon" title="LinkedIn">
                    <i class="fa fa-linkedin"></i>
                </a>
                <a href="https://www.instagram.com/zieltech.academy/" target="_blank" class="gdlr-core-social-network-icon" title="Instagram">
                    <i class="fa fa-instagram"></i>
                </a>
                </div>
                <h4 class="mt-3">{{ $branch->name }}</h4>
                <h6 style="max-width: 300px; word-wrap: break-word;">{{ $branch->address }}</h6>
                <p><i class="fa-solid fa-phone me-3"></i>{{ $branch->contact_number }}</p>
            </div>
        </div>
        @endforeach
    </div>

        <!-- <div class="sliderBar" data-aos="fade-up">
            <div class="container">
                <div class="row">
                    <div class="main-slider-div">
                        <div class="left-btn"><button id="leftBtn"><i class="fa-solid fa-arrow-left"></i></button></div>
                        <div class="sliderBar-items" id="slider">
                            <div class="items itemActive"><i class="fa-solid fa-city me-3"></i>Calicut</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Kochi</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Trivandrum</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Kannur</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Calicut</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Kochi</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Trivandrum</div>
                            <div class="items"><i class="fa-solid fa-city me-3"></i>Kannur</div>
                        </div>
                        <div class="right-btn"><button id="rightBtn"><i class="fa-solid fa-arrow-right"></i></button></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" data-aos="fade-up">
            <div class="row map-section">
                <div class="col-lg-6 mt-5" data-aos="fade-up">
                    <img class="img-fluid" src="{{ asset('web/upload/academy-img.webp') }}" alt="">
                    <div class="maps">
                        <div class="map-div">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7833.967549937794!2d76.00318184246765!3d10.964596989796453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7b46389fc5d29%3A0x86a29b9fcaa8cb02!2sRandathani%2C%20Kerala%20676510!5e0!3m2!1sen!2sin!4v1742106869507!5m2!1sen!2sin" width="100%" height="100" style="border:0;border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="map-div">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7833.967549937794!2d76.00318184246765!3d10.964596989796453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7b46389fc5d29%3A0x86a29b9fcaa8cb02!2sRandathani%2C%20Kerala%20676510!5e0!3m2!1sen!2sin!4v1742106869507!5m2!1sen!2sin" width="100%" height="100" style="border:0;border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="map-div">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7833.967549937794!2d76.00318184246765!3d10.964596989796453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7b46389fc5d29%3A0x86a29b9fcaa8cb02!2sRandathani%2C%20Kerala%20676510!5e0!3m2!1sen!2sin!4v1742106869507!5m2!1sen!2sin" width="100%" height="100" style="border:0;border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5" data-aos="fade-up">
                    <h3>Ziel tech academy</h3>
                    <p>Location</p>
                    <div class="contact-address mt-4">
                        <div class="contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="">
                            <h6>Contact</h6>
                            <h5>+1-2351-2361-355</h5>
                        </div>
                    </div>
                    <div class="contact-address mt-4">
                        <div class="contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="">
                            <h6>Address</h6>
                            <h5>New York ,78 E 1st St,10009</h5>
                        </div>
                    </div>
                    <div class="contact-address mt-4">
                        <div class="contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="">
                            <h6>Hours</h6>
                            <h5>Mon - Sat: 10:00 AM - 8:00 PM</h5>
                        </div>
                    </div>
                    <button class="get-btn mt-5">Get Direction</button>
                </div>
                <div class="col-lg-6 mt-5" data-aos="fade-up">
                    <img class="img-fluid" src="{{ asset('web/upload/academy-img.webp') }}" alt="">
                    <div class="maps">
                        <div class="map-div">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7833.967549937794!2d76.00318184246765!3d10.964596989796453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7b46389fc5d29%3A0x86a29b9fcaa8cb02!2sRandathani%2C%20Kerala%20676510!5e0!3m2!1sen!2sin!4v1742106869507!5m2!1sen!2sin" width="100%" height="100" style="border:0;border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="map-div">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7833.967549937794!2d76.00318184246765!3d10.964596989796453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7b46389fc5d29%3A0x86a29b9fcaa8cb02!2sRandathani%2C%20Kerala%20676510!5e0!3m2!1sen!2sin!4v1742106869507!5m2!1sen!2sin" width="100%" height="100" style="border:0;border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="map-div">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7833.967549937794!2d76.00318184246765!3d10.964596989796453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7b46389fc5d29%3A0x86a29b9fcaa8cb02!2sRandathani%2C%20Kerala%20676510!5e0!3m2!1sen!2sin!4v1742106869507!5m2!1sen!2sin" width="100%" height="100" style="border:0;border-radius: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5" data-aos="fade-up">
                    <h3>Ziel tech academy</h3>
                    <p>Location</p>
                    <div class="contact-address mt-4">
                        <div class="contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="">
                            <h6>Contact</h6>
                            <h5>+1-2351-2361-355</h5>
                        </div>
                    </div>
                    <div class="contact-address mt-4">
                        <div class="contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="">
                            <h6>Address</h6>
                            <h5>New York ,78 E 1st St,10009</h5>
                        </div>
                    </div>
                    <div class="contact-address mt-4">
                        <div class="contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="">
                            <h6>Hours</h6>
                            <h5>Mon - Sat: 10:00 AM - 8:00 PM</h5>
                        </div>
                    </div>
                    <button class="get-btn mt-5">Get Direction</button>
                </div>
            </div>
        </div> -->
    </section>
    <style>
        .banner-sections {
        background-position: center;
        background-size: cover;
        height: 75vh;
        display: flex;
        align-items: center;
        margin-top: 100px;
        justify-content: center;
    }
    @media all and (max-width:768px) {
        .banner-sections {
        height: 36vh;
    }
    }
    </style>
    <script>
        AOS.init({
            duration: 1200,
            once: true,
        })

        // slider script
        document.addEventListener("DOMContentLoaded", () => {
            const slider = document.getElementById("slider");
            const leftBtn = document.getElementById("leftBtn");
            const rightBtn = document.getElementById("rightBtn");
            const items = document.querySelectorAll(".items");
            let scrollAmount = 0;
            const itemWidth = items[0].offsetWidth + 10; // Including margin

            // Click on item to activate it
            items.forEach(item => {
                item.addEventListener("click", () => {
                    document.querySelector(".itemActive")?.classList.remove("itemActive");
                    item.classList.add("itemActive");
                });
            });

            // Scroll to the right
            rightBtn.addEventListener("click", () => {
                if (scrollAmount < slider.scrollWidth - slider.clientWidth) {
                    scrollAmount += itemWidth;
                    slider.scrollTo({ left: scrollAmount, behavior: "smooth" });
                }
            });

            // Scroll to the left
            leftBtn.addEventListener("click", () => {
                if (scrollAmount > 0) {
                    scrollAmount -= itemWidth;
                    slider.scrollTo({ left: scrollAmount, behavior: "smooth" });
                }
            });
        });
    </script>
    @endsection