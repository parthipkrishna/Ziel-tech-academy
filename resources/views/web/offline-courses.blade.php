@extends('web.layouts.layout')
@section('offline-meta')
<title>Smartphone & Laptop Repair Courses in Kerala | Zieltech Academy Calicut </title>
<meta name="description" content="Zieltech Academy in Calicut offers 100% practical offline training in smartphone and laptop repair. Join Kerala’s best technical institute with chip-level courses and placement support."/>
<meta name="keywords" content="Smartphone Engineering Course Kerala, Laptop Chip Level Training Kerala, Mobile Phone Repair Course Calicut, Laptop Servicing Institute Kerala, Best Technical Institute in Calicut, Mobile and Laptop Repairing Course with Placement, Smartphone Technician Course Kerala, Practical Technical Course Kerala">
@endsection
@section('campus')
    <!-- navbar end -->
    <!-- banner section -->
    @if(count($banner) > 0)
    @foreach ($banner as $item)
        <section class="banner-section" 
            style="background-image: url('{{ $item->image_url ? Storage::url($item->image_url) : asset('web/upload/welcome-banner.webp') }}');">
            <div class="banner-container">
                <h2 class="banner_header">Welcome to <span class="header2">Zieltech Academy</span></h2>
            </div>
        </section>
    @endforeach
    @else
        <section class="banner-section default-banner">
            <div class="container">
                <div class="row"  data-aos="fade-left" data-aos-duration="2000">
                <div class="banner-container">
                    <h1 class="banner_header">Welcome to <span class="header2">Zieltech Academy</span></h1>
                </div>
            </div>
        </section>
    @endif
    {{-- <div class="kingster-page-wrapper botton-navbar-wrapper" style="margin-top: 0px;" id="kingster-page-wrapper">
        <div class="gdlr-core-page-builder-body" >
            <div class="gdlr-core-pbf-wrapper " style="padding: 0px 0px 0px 0px;">
                <div class="gdlr-core-pbf-background-wrap" style="background-color: #262626 ;"></div>
                <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
                    <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-pbf-wrapper-full-no-space">
                        <div class="gdlr-core-pbf-column gdlr-core-column-15 gdlr-core-column-first" data-skin="Column White" data-aos="fade-left" data-aos-duration="500">
                            <div class="gdlr-core-pbf-column-content-margin gdlr-core-js  slider-link-1" style="padding: 70px 0px 70px 0px;" data-sync-height="column-height" data-sync-height-center>
                                <div class="gdlr-core-pbf-background-wrap">
                                    <div class="gdlr-core-pbf-background gdlr-core-parallax gdlr-core-js" 
                                        style="background-image: url('{{ asset('web/upload/Campus.webp') }}');
                                        background-size: cover; 
                                        background-position: center;" 
                                        data-parallax-speed="0.1">
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js  gdlr-core-sync-height-content">
                                    <div class="gdlr-core-pbf-element">
                                        <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-center-align gdlr-core-with-caption gdlr-core-item-pdlr" style="padding-bottom: 0px;">
                                            <div class="gdlr-core-column-service-media gdlr-core-media-image" style="margin-bottom: 20px;"><img src="{{ asset('web/upload/hp2-col-1-icon.png') }}" alt="" width="40" height="40" title="hp2-col-1-icon" /></div>
                                            <div class="gdlr-core-column-service-content-wrapper">
                                                <div class="gdlr-core-column-service-title-wrap">
                                                    <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="font-size: 19px ;font-weight: 700 ;letter-spacing: 0px ;text-transform: none ;">Campus</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="gdlr-core-pbf-column-link" href="branches.html"></a>
                            </div>
                        </div>
                        <div class="gdlr-core-pbf-column gdlr-core-column-15" data-skin="Column White" data-aos="fade-left" data-aos-duration="1000">
                            <div class="gdlr-core-pbf-column-content-margin gdlr-core-js  slider-link-2" style="padding: 70px 0px 70px 0px;" data-sync-height="column-height" data-sync-height-center>
                                <div class="gdlr-core-pbf-background-wrap">
                                <div class="gdlr-core-pbf-background gdlr-core-parallax gdlr-core-js" 
                                    style="background-image: url('{{ asset('web/upload/Placements.webp') }}'); background-size: cover; background-position: center;" 
                                    data-parallax-speed="0.1">
                                </div>
                                </div>
                                <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js  gdlr-core-sync-height-content">
                                    <div class="gdlr-core-pbf-element">
                                        <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-center-align gdlr-core-with-caption gdlr-core-item-pdlr" style="padding-bottom: 0px;">
                                            <div class="gdlr-core-column-service-media gdlr-core-media-image" style="margin-bottom: 20px;"><img src="{{ asset('web/upload/hp2-col-2-icon.png') }}" alt="" width="49" height="45" title="hp2-col-2-icon" /></div>
                                            <div class="gdlr-core-column-service-content-wrapper">
                                                <div class="gdlr-core-column-service-title-wrap">
                                                    <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="font-size: 19px ;font-weight: 700 ;letter-spacing: 0px ;text-transform: none ;">Placements</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="gdlr-core-pbf-column-link" href="#" target="_self"></a>
                            </div>
                        </div>
                        <div class="gdlr-core-pbf-column gdlr-core-column-15" data-skin="Column White" data-aos="fade-left" data-aos-duration="1500">
                            <div class="gdlr-core-pbf-column-content-margin gdlr-core-js  slider-link-3" style="padding: 70px 0px 70px 0px;" data-sync-height="column-height" data-sync-height-center>
                            <div class="gdlr-core-pbf-background-wrap">
                                <div class="gdlr-core-pbf-background gdlr-core-parallax gdlr-core-js" 
                                    style="background-image: url('{{ asset('web/upload/Branches.webp') }}'); background-size: cover; background-position: center;" 
                                    data-parallax-speed="0.1">
                                </div>
                            </div>
                                <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js  gdlr-core-sync-height-content">
                                    <div class="gdlr-core-pbf-element">
                                        <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-center-align gdlr-core-with-caption gdlr-core-item-pdlr" style="padding-bottom: 0px;">
                                            <div class="gdlr-core-column-service-media gdlr-core-media-image" style="margin-bottom: 20px;"><img src="{{ asset('web/upload/hp2-col-3-icon.png') }}" alt="" width="50" height="44" title="hp2-col-3-icon" /></div>
                                            <div class="gdlr-core-column-service-content-wrapper">
                                                <div class="gdlr-core-column-service-title-wrap">
                                                    <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="font-size: 19px ;font-weight: 700 ;letter-spacing: 0px ;text-transform: none ;">Branches</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="gdlr-core-pbf-column-link" href="#" target="_self"></a>
                            </div>
                        </div>
                        <div class="gdlr-core-pbf-column gdlr-core-column-15" data-skin="Column White" data-aos="fade-left" data-aos-duration="2000">
                            <div class="gdlr-core-pbf-column-content-margin gdlr-core-js  slider-link-4" style="padding: 70px 0px 70px 0px;" data-sync-height="column-height" data-sync-height-center>
                                <div class="gdlr-core-pbf-background-wrap">
                                    <div class="gdlr-core-pbf-background gdlr-core-parallax gdlr-core-js" style="background-image: url('{{ asset('web/upload/Contact.webp') }}') ;background-size: cover ;background-position: center ;" data-parallax-speed="0.1"></div>
                                </div>
                                <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js  gdlr-core-sync-height-content">
                                    <div class="gdlr-core-pbf-element">
                                        <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-center-align gdlr-core-with-caption gdlr-core-item-pdlr" style="padding-bottom: 0px;">
                                            <div class="gdlr-core-column-service-media gdlr-core-media-image" style="margin-bottom: 20px;"><img src="{{ asset('web/upload/hp2-col-4-icon.png') }}" alt="" width="41" height="41" title="hp2-col-4-icon" /></div>
                                            <div class="gdlr-core-column-service-content-wrapper">
                                                <div class="gdlr-core-column-service-title-wrap">
                                                    <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="font-size: 19px ;font-weight: 700 ;letter-spacing: 0px ;text-transform: none ;">Contact</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="gdlr-core-pbf-column-link" href="#" target="_self"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- sp campus section -->
    <section class="spCampus-section">
        <div class="container">
            <div class="row campus-parent">
                <div class="col-lg-6 campus-datas" data-aos="fade-left" data-aos-duration="1000">
                    <img class="img-fluid" src="{{ asset('web/upload/campus-logo.webp') }}" alt="">
                    <h2 class="mt-3">Special Campus Tour</h2>
                    <p>Campus on a tour designed for prospective graduate and
                        professional students. You will see how our university like, facilities, students and life in this university. Meet our graduate admissions representative to learn more about our graduate programs and decide what it the best for you.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img class="img-fluid" src="{{ asset('web/upload/campus-img.webp') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- programs section -->
        <section class="programs-section" id="programs-section" data-aos="fade-left" data-aos-duration="1000">
        <div class="container">
            <div class="row">
                <div class="heading">
                    <h2>Hands on Learning with Offline Training </h2>
                    <h6>Choose Your Path – Offline Training Available</h6>
                </div>
                @foreach ($courses as $item )
                <div class="col-lg-6 col-md-6 mt-5" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                        <img src="{{ env('STORAGE_URL') . '/' . optional($item->offlineCourseTypes->first())->cover_image ?? 'N/A' }}" class="img-fluid course-image" alt="">
                        <div class="card-datas">
                            <h3>{{ $item->name }}</h3>
                            <p><i class="fa-solid fa-clock me-2"></i> {{ $item->offlineCourseTypes->first()->duration ?? 'N/A' }} Months</p>
                            {{-- <div class="totalPrice-box mt-3">
                                                <p>Monthly fee : <span>₹{{ $item->monthly_fee }}</span></p>
                                            </div> --}}
                            {{-- <h4 class="mt-1">Course Fee: <span>₹{{ $item->total_fee }}</span></h4> --}}
                            <!-- <div class=""><i class="fa-solid fa-arrow-trend-up me-2 text-white"></i>
                                <span style="color: var(--Congress-Blue-200, #BCDEFB); font-size: 18px; font-weight: 400;">Basic training</span>
                                <span style="color: var(--Congress-Blue-100, #E1EFFD); font-size: 18px; font-weight: 400;"> - </span>
                                <span style="color: var(--Congress-Blue-100, #E1EFFD); font-size: 18px; font-weight: 500;">5 Months</span>
                            </div>
                            <div class=""><i class="fa-solid fa-arrow-trend-up me-2 mt-4 text-white"></i>
                                <span style="color: var(--Congress-Blue-200, #BCDEFB); font-size: 18px; font-weight: 400;">On job training</span>
                                <span style="color: var(--Congress-Blue-100, #E1EFFD); font-size: 18px; font-weight: 400;"> - </span>
                                <span style="color: var(--Congress-Blue-100, #E1EFFD); font-size: 18px; font-weight: 500;">12 Months</span>
                            </div> -->  
                             <!-- Centering button --> 
                            <a href="#register-section-offline"> <button class="mt-3">Enroll Now</button></a> <!-- Added Bootstrap button style -->
                        </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- course modules section -->
    <section id="course-modules" class="modules-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <h3>Course Modules & Details</h3>
                <div class="col-lg-5 mt-5 smartphone-img" data-aos="fade-up">
                    <img class="img-fluid" src="{{ asset('web/upload/Course-module-2.webp') }}" alt="">
                </div>
                <div class="col-lg-1 mt-5"></div>
                <div class="col-lg-6 mt-5 courseModules-datas" data-aos="fade-up">
                    @foreach ($courses as $item )
                        <div class="courseModules-datas-main">
                            <h3>{{ $item->name }}</h3>
                            @if ($item->offlineSubjects->isNotEmpty())
                                @foreach ($item->offlineSubjects as $subject)
                                    <div class="courseModules-datas-details mt-3">
                                        <img class="img-fluid" src="{{ asset('web/upload/record-icon.png') }}" alt="">
                                        <h6 class="mt-2">{{ $subject->name }}</h6>
                                    </div>
                                @endforeach
                            @else
                            <p>No subjects available</p>
                            @endif
                        </div>
                     @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- campus facilities -->
    <section class="campus-facilities-section" data-aos="fade-up">
        <div class="container">
            <h3>Campus Facilities</h3>
            @foreach ($campus as $index => $item)
            <div class="row campus-facilities" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                @if ($index % 2 == 0)
                    <!-- Text Left - Image Right -->
                    <div class="col-lg-6 mt-5 col-md-6 order-2 order-md-1" data-aos="fade-up">
                        <p>
                            <strong>{{ Str::before($item->description, ':') }}</strong> 
                            <br>
                            <span style="font-size: 20px;">{{ Str::after($item->description, ':') }}</span>
                        </p>
                    </div>
                    <div class="col-lg-6 mt-5 col-md-6 order-1 order-md-2" data-aos="fade-up">
                        <img class="img-fluid" src="{{ $item->image ? env('STORAGE_URL') . '/' . $item->image : asset('web/upload/default-image.jpg') }}" alt="">
                    </div>
                @else
                    <!-- Image Left - Text Right -->
                    <div class="col-lg-6 mt-5 col-md-6 order-1 order-md-1" data-aos="fade-up">
                        <img class="img-fluid" src="{{ $item->image ? env('STORAGE_URL') . '/' . $item->image : asset('web/upload/default-image.jpg') }}" alt="">
                    </div>
                    <div class="col-lg-6 mt-5 col-md-6 order-2 order-md-2" data-aos="fade-up">
                        <p>
                            <strong>{{ Str::before($item->description, ':') }}</strong> 
                            <br>
                            <span style="font-size: 20px;">{{ Str::after($item->description, ':') }}</span>
                        </p>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    <!--  Events & Programs section -->
    <!-- <div class="gdlr-core-pbf-wrapper">
        <div class="gdlr-core-pbf-wrapper-content gdlr-core-js">
            <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-pbf-wrapper-full">
                <div class="gdlr-core-pbf-element">
                    <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix gdlr-core-center-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr" style="padding-bottom: 60px;">
                        <div class="gdlr-core-title-item-title-wrap clearfix">
                            <h3 class="gdlr-core-title-item-title gdlr-core-skin-title" style="text-transform: none; color: #E1EFFD; font-size: 48px; font-weight: 400; text-align: center;">
                                Events & Programs
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="gdlr-core-pbf-element">
                    <div class="gdlr-core-gallery-item gdlr-core-item-pdb clearfix gdlr-core-gallery-item-style-scroll gdlr-core-item-pdlr">
                        <div class="gdlr-core-sly-slider gdlr-core-js-2" style="overflow: hidden;">
                            <div class="gdlr-core-row" style="display: flex; flex-wrap: nowrap; gap: 70px; padding: 10px 0;">
                                @foreach ($events as $event)
                                    @foreach ($event->media as $media)
                                        <div class="gdlr-core-gallery-item" style="flex: 0 0 500px; scroll-snap-align: start;">
                                            <div class="gdlr-core-gallery-list" style="height: 100%;">
                                                <div class="gdlr-core-media-image" style="height: 350px; width: 600px; overflow: hidden; position: relative; border-radius: 0px;">
                                                    <a class="gdlr-core-lightgallery gdlr-core-js" href="{{ env('STORAGE_URL') . '/' . $media->media_url }}" data-lightbox-group="gdlr-core-img-group-3">
                                                        <img src="{{ env('STORAGE_URL') . '/' . $media->media_url }}" alt="{{ $event->name }}" 
                                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                                        <span class="gdlr-core-image-overlay gdlr-core-gallery-image-overlay gdlr-core-center-align">
                                                            <i class="gdlr-core-image-overlay-icon gdlr-core-size-22 fa fa-search"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="event-info" style="text-align: left; padding: 10px;">
                                                    <h4 style="color: white; margin: 0; font-size: 16px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $event->name }}</h4>
                                                    <p style="color: #E1EFFD; margin: 5px 0 0; font-size: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                        {{ $event->description }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- contact section -->
    <section class="contact-section" data-aos="fade-up" id="register-section-offline">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 inputData mt-5" data-aos="fade-up">
                <!-- @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif -->
                    <h2>Enquire <span>Now</span></h2>
                    <form class="needs-validation" id="EnrollmentForm" method="POST" action="{{ route('offline.store') }}" enctype="multipart/form-data"  validate>
                        @csrf
                        <div class="form-item">
                            <input name="first_name" type="text" id="first_name" autocomplete="off" required>
                            <label>Name <span>*</span></label>
                        </div>
                        <div class="form-item">
                            <input name="email"type="email" id="email" autocomplete="off" required>
                            <label>Email <span>*</span></label>
                        </div>
                        <input type="hidden" name="status" value="enrolled">
                        <div class="form-item">
                            <input name="phone" type="text" id="number" autocomplete="off" required>
                            <label>Phone number <span>*</span></label>
                        </div>
                        <div class="dropdown-container">
                                <select class="form-select" id="example-select" name="course_id" required>
                                    <option value="">What are you looking for ?</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            <i class="dropdown-icon"></i>
                        </div> 
                        <div id="errorMessages" style="color: red;"></div>
                        <button type="submit" id="submitBtn">Register Now</button>
                        <div id="successMessage" style="color: green; margin-top: 10px;"></div>                 
                    </form>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-5 mt-5 contact-img1" data-aos="fade-up">
                <div class="">
                    <div class="quick-link">
                        <h2>Quick Links</h2>
                        <h2><i class="fa-solid fa-arrow-trend-up"></i></h2>
                    </div>   
                    @foreach ($quickLinks as $link)
                        <h5>
                            @if ($link->type === 'LINK')
                                <!-- Redirect to URL when clicked -->
                                <a href="{{ $link->url }}" target="_blank">{{ $link->title }}</a>
                            @elseif ($link->type === 'ATTACHMENT' && $link->attachment)
                                <!-- Redirect to attachment file when clicked -->
                                <a href="{{ asset('storage/' . $link->attachment) }}" target="_blank">{{ $link->title }}</a>
                            @else
                                {{ $link->title }}
                            @endif
                        </h5>
                    @endforeach
                </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .programs-section .card {
        background-color: transparent;
        border-radius: 40px; 
        outline: 0.50px var(--color-azure-89, #DEE2E6) solid; 
        outline-offset: -0.50px;
        margin: 0px;
        padding: 0px;
        height: 100%;
        width: 90%;
        margin-left: 18px;
    }
    .programs-section .card button {
        background: var(--Black-Pearl-200, #B2E0FF);
        border-radius: 44px;
        color: var(--Congress-Blue-900, #0F3E6B); 
        font-size: 22px; 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-weight: 500;
        border: 0px;
        padding: 10px 30px;
        width: 100% !important;
    }
    .card .card-body .course-image {
        height: 300px !important;
        width: 100%;
        border-top-left-radius: 30px; /* Adjust the value as needed */
        border-top-right-radius: 30px;
        display: block;
        max-width: 100%; /* Ensures the image is responsive */
    }
    .programs-section {
        background: linear-gradient(302deg, #0B4E89 0%, #031423 100%);
        padding: 130px 0px 200px 0px;
    }

    .programs-section .heading {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .programs-section .heading h2 {
        color: #E1EFFD;
        font-size: 38px !important; 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
        font-weight: 400;
    }

    .programs-section .heading h6 {
        color: #E1EFFD; 
        font-size: 24px; 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
        font-weight: 400;
    }
    .programs-section .card .card-body {
        margin: 0px;
        padding: 0px;
    }
    .programs-section
    .card .card-body 
    .card-datas {
        padding: 20px 10px 20px 20px;
    }

    .programs-section .card h3 {
        color: var(--Congress-Blue-100, #E1EFFD); 
        font-size: 28px; 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
        font-weight: 500;
    }

    .programs-section .card p {
        color: #6EA4BF; 
        font-size: 16px;
    }
    .programs-section .card .card-body .card-datas h4 {
    color: var(--Congress-Blue-50, #F0F7FF);
    font-size: 24px;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-weight: 300;
    }
    .programs-section .card .card-body .totalPrice-box {
        outline: 2px #002C54 solid;
        outline-offset: -2px;
        padding: 10px 20px;
        border-radius: 14px;
        width: fit-content;
    }
    .programs-section .card .card-body .totalPrice-box p {
        color: var(--Color-stroke, #C8C8C8);
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 0px !important;
    }
    </style>
<script type="text/javascript" src="{{ asset('web/js/jquery/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('web/js/jquery/jquery-migrate.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const gallery = document.querySelector('.gdlr-core-sly-slider');
    const scrollProgress = document.createElement('div');
    scrollProgress.className = 'scroll-progress-bar';
    
    // Append the progress bar to the gallery container
    if (gallery) {
        gallery.parentElement.insertBefore(scrollProgress, gallery.nextSibling);
        
        // Horizontal scroll with mouse wheel
        gallery.addEventListener('wheel', function(e) {
            if (e.deltaY !== 0) {
                e.preventDefault();
                this.scrollLeft += e.deltaY;
                updateScrollProgress();
            }
        });
        
        // Update progress bar on manual scroll (drag)
        gallery.addEventListener('scroll', updateScrollProgress);
        
        function updateScrollProgress() {
            const scrollWidth = gallery.scrollWidth - gallery.clientWidth;
            const scrollPercentage = (gallery.scrollLeft / scrollWidth) * 100;
            scrollProgress.style.width = `${scrollPercentage}%`;
        }
        
        // Initialize progress bar
        updateScrollProgress();
    }
});
</script>
<script>
    document.querySelectorAll('a[href="#register-section-offline"]').forEach(button => {
    button.addEventListener('click', function(e) {
        const targetSection = document.getElementById('register-section-offline');
        
            // If the section exists on this page, scroll smoothly
            if (targetSection) {
                e.preventDefault(); // Prevent default anchor jump
                targetSection.scrollIntoView({ behavior: 'smooth' });
                history.pushState(null, null, '#register-section-offline'); // Update URL
            }
            // If section doesn't exist, redirect to the correct page with the hash
            else {
                e.preventDefault(); // Prevent default behavior
                window.location.href = "/campus/#register-section-offline"; // Redirect to homepage (adjust if needed)
            }
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#EnrollmentForm').on('submit', function(e) {
        e.preventDefault();

        $('#submitBtn').attr('disabled', true);
        $('#errorMessages').html('');

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                // Show success message or redirect
                $('#successMessage').html('Thanks for registering!');
                $('#EnrollmentForm')[0].reset();
                $('#submitBtn').attr('disabled', false);
            },
            error: function(xhr) {
                $('#submitBtn').attr('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<ul>';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    errorHtml += '</ul>';
                    $('#errorMessages').html(errorHtml);
                } else if(xhr.responseJSON?.message) {
                    $('#errorMessages').html('<p>' + xhr.responseJSON.message + '</p>');
                } else {
                    $('#errorMessages').html('<p>Something went wrong. Please try again.</p>');
                }
            }
        });
    });
</script>
    @endsection