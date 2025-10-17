@extends('web.layouts.layout')
@section('about')
       <!-- Banner Section -->
    @if($banner->isNotEmpty())
        @foreach ($banner as $item)
            <section class="banner-sections-abt" 
                style="background-image: url('{{ $item->image_url ? Storage::url($item->image_url) : asset('web/upload/about-us.webp') }}');">
                <div class="container">
                    <div class="row">
                        <h1>{{ $item->title ?? 'About Us' }}</h1>
                        <h6>{{ $item->short_desc ?? 'Get In Touch' }}</h6>
                    </div>
                </div>
            </section>
        @endforeach
    @else
        <section class="banner-sections-abt" style="background-image: url('{{ asset('web/upload/about-us.webp') }}');">
            <div class="container">
                <div class="row" data-aos="fade-left">
                    <h1>About Us</h1>
                    <!-- <h6>Get In Touch</h6> -->
                </div>
            </div>
        </section>
    @endif

    <!-- about section -->
    <section class="about-section" data-aos="fade-left">
        <div class="container">
            <div class="row about-details">
                <div class="col-lg-4">
                    <h2>About Ziel</h2>
                </div>
                <div class="col-lg-8" data-aos="fade-left" data-aos-duration="2000">
                    <p>Zieltech Academy is a premier training institution specializing in mobile and laptop repair. We are dedicated to empowering individuals with world-class technical education, cultivating a community of highly skilled professionals. Committed to innovation, we aim to lead the mobile service and IT repair industries by providing cutting-edge training solutions, fostering growth, and creating pathways to sustainable careers. Join us and shape your future with confidence and expertise.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- mission vision -->
    <section class="missionVision-section" data-aos="fade-up">
        <div class="container">
            @foreach ($info as $item )
            <div class="row">
                <div class="col-lg-6" data-aos="fade-up">
                    <img class="img-fluid mb-3" src="{{ asset('web/upload/Growthvision.webp') }}" alt="">
                    <h5>Our Vision</h5>
                    <p>{{ $item->vision }}</p>
                </div>
                <div class="col-lg-6" data-aos="fade-up">
                    <img class="img-fluid mb-3" src="{{ asset('web/upload/Campaign.webp') }}" alt="">
                    <h5>Our Mission</h5>
                    <p>{{ $item->mission }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- offer section -->
    <section class="offer-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="heading" data-aos="fade-up">
                    <h3>What we offer</h3>
                    <h6>Certification & Career Support at Zieltech Academy</h6>
                </div>

                @foreach (explode('.', $item->offerings) as $offering)
                    @php
                        $offerParts = explode(':', $offering, 2);
                    @endphp
                    @if(count($offerParts) == 2)
                        <div class="col-lg-4 col-md-6 mt-5" data-aos="fade-up">
                            <h4>{{ trim($offerParts[0]) }}</h4>
                            <p>{{ trim($offerParts[1]) }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endforeach
    <!-- counting section -->
    <!-- <section class="counting-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="headings" data-aos="fade-up">
                    <h2>Our Success Rates</h2>
                    <h6>Track completion ,certification and Job placement</h6>
                </div>
                <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                          <h2 class="counter" data-target="1200">1200+</h2>
                          <p>Students Trained</p>  
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                          <h2 class="counter" data-target="500">500+</h2>
                          <p>Industry collaboration</p>  
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                          <h2 class="counter" data-target="1500">1500+</h2>
                          <p>Job Placement</p>  
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </section> -->
 @endsection   