@extends('web.layouts.layout')
@section('placement')
<!-- navbar section end -->
<div class="kingster-page-wrapper" style="margin-top: 100px; background-color: #F6FBFF !important;" id="kingster-page-wrapper">
    <div class="gdlr-core-page-builder-body">
        <div class="gdlr-core-pbf-wrapper " style="padding: 332px 0px 75px 0px;" id="gdlr-core-wrapper-1">
            @if(count($banner) > 0)
            @foreach ($banner as $item)
                <div class="gdlr-core-pbf-background-wrap">
                    <div class="gdlr-core-pbf-background gdlr-core-parallax gdlr-core-js" 
                        style="background-image: url('{{ $item->image_url ? Storage::url($item->image_url) : asset('web/upload/placement_banners.webp') }}'); 
                        background-size: cover; 
                        background-position: center;" 
                        data-parallax-speed="0">
                    </div>
                </div>
            @endforeach
            @else
                <div class="gdlr-core-pbf-background-wrap">
                    <div class="gdlr-core-pbf-background gdlr-core-parallax gdlr-core-js" 
                        style="background-image: url('{{ asset('web/upload/placement_banners.webp') }}'); 
                        background-size: cover; 
                        background-position: center;" 
                        data-parallax-speed="0">
                    </div>
                </div>   
            @endif     
            <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
                <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
                    <div class="gdlr-core-pbf-column gdlr-core-column-20 gdlr-core-column-first placement-head">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js ">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js " data-aos="fade-left" data-aos-duration="1000">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-top gdlr-core-item-pdlr">
                                        <div class="gdlr-core-title-item-title-wrap clearfix">
                                            <h3 class="gdlr-core-title-item-title gdlr-core-skin-title " style="font-size: 40px ;font-weight: 700 ;letter-spacing: 0px ;text-transform: none ;color: #ffffff ;">Placements</h3></div>
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-divider-item gdlr-core-divider-item-normal gdlr-core-item-pdlr gdlr-core-left-align">
                                        <div class="gdlr-core-divider-container" style="max-width: 300px ;">
                                            <div class="gdlr-core-divider-line gdlr-core-skin-divider" style="border-color: #0D4F8B ;border-bottom-width: 5px ;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-40">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js ">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js ">
                                <div class="gdlr-core-pbf-element" data-aos="fade-left" data-aos-duration="1500">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 0px ;">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 18px ;text-transform: none ;color: #ffffff ;">
                                            <p>Our placement program connects students with top employers, ensuring career success through hands-on training, industry exposure, and expert guidance. We foster partnerships with leading companies to provide job opportunities, internships, and career counseling, empowering students with the skills and confidence needed to excel in their chosen fields.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="gdlr-core-pbf-wrapper " style="padding: 90px 0px 90px 0px;" >
            <div class="gdlr-core-pbf-background-wrap"></div>
            <div class="gdlr-core-pbf-wrapper-content gdlr-core-js" >
                <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
                    <div class="gdlr-core-pbf-element" data-aos="fade-up" data-aos-duration="1500">
                        <div class="" style="display: flex;justify-content: space-between;flex-wrap: wrap;">
                            <h3 style="background: linear-gradient(90deg, #E5B92299 10%, #0D4F8B 79%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 40px;font-weight: 400; padding-left: 17px;">Career Support at Ziel</h3>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-20 gdlr-core-column-first" data-skin="Green Title">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js ">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js ">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-left-align gdlr-core-column-service-icon-top gdlr-core-no-caption gdlr-core-item-pdlr" style="padding-bottom: 2px;">
                                        <div class="gdlr-core-column-service-content-wrapper" data-aos="fade-up" data-aos-duration="2000">
                                            <div class="gdlr-core-column-service-title-wrap" style="margin-bottom: 0px;margin-top: 50px;">
                                                <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="color: var(--Congress-Blue-100, #0D4F8B); font-size: 32px;font-weight: 500;text-transform: none ;">Certification</h3></div>
                                            <div class="gdlr-core-column-service-content" style="color: #0d4f8bb0; font-size: 18px; font-weight: 400; line-height: 30.20px;text-transform: none ;margin-top: 20px;">
                                                <p>Zieltech Academy provides industry-focused certifications, offering hands-on training and expert guidance for career growth.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-20" data-skin="Green Title">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js ">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js ">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-left-align gdlr-core-column-service-icon-top gdlr-core-no-caption gdlr-core-item-pdlr" style="padding-bottom: 2px;">
                                        <div class="gdlr-core-column-service-content-wrapper" data-aos="fade-up" data-aos-duration="2100">
                                            <div class="gdlr-core-column-service-title-wrap" style="margin-bottom: 0px;margin-top: 50px;">
                                                <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="color: var(--Congress-Blue-100, #0D4F8B); font-size: 32px;font-weight: 500;text-transform: none ;">Interview preparation</h3></div>
                                            <div class="gdlr-core-column-service-content" style="color: #0d4f8bb0; font-size: 18px; font-weight: 400; line-height: 30.20px;text-transform: none ;margin-top: 20px;">
                                                <p>Prepare for your dream job with Zieltech Academy! Get guidance, mock interviews, and confidence to ace your interviews!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-20" data-skin="Green Title">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js ">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js ">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-column-service-item gdlr-core-item-pdb  gdlr-core-left-align gdlr-core-column-service-icon-top gdlr-core-no-caption gdlr-core-item-pdlr" style="padding-bottom: 2px;">
                                        <div class="gdlr-core-column-service-content-wrapper" data-aos="fade-up" data-aos-duration="2200">
                                            <div class="gdlr-core-column-service-title-wrap" style="margin-bottom: 0px;margin-top: 50px;">
                                                <h3 class="gdlr-core-column-service-title gdlr-core-skin-title" style="color: var(--Congress-Blue-100, #0D4F8B); font-size: 32px;font-weight: 500;text-transform: none ;">Job search assistance</h3></div>
                                            <div class="gdlr-core-column-service-content" style="color: #0d4f8bb0; font-size: 18px; font-weight: 400; line-height: 30.20px;text-transform: none ;margin-top: 20px;">
                                                <p>Get expert job search help with Zieltech Academy! From resumes to interviews, we guide you to success faster!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <section style="background: linear-gradient(302deg, #0B4E89 0%, #031423 100%);padding: 80px 0px;">
            <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
                <div class="gdlr-core-pbf-element">
                    <div class="" style="display: flex;justify-content: space-between;flex-wrap: wrap;">
                        <h3 style="color:#E1EFFD; ;font-size: 40px;font-weight: 400;">Our Hiring partners</h3>
                    </div>
                </div>
            </div>
            <div class="gdlr-core-pbf-wrapper " style="padding: 0px 0px 0px 0px;margin-top: 50px;">
            @if(count($placement) > 0)
            <div class="gdlr-core-pbf-wrapper-content gdlr-core-js">
                <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container Branches-images" 
                    style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: space-between; margin-bottom: 30px;">
                    @foreach ($placement as $index => $item)
                        <img style="height: 90px; width: auto;" src="{{ env('STORAGE_URL') . '/' . $item->image }}" alt="">

                        {{-- Add a row break after every 5 images --}}
                        @if(($index + 1) % 5 == 0)
                            </div>
                            <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container Branches-images" 
                                style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: space-between;">
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </section> -->
        <!-- <section class="" style="padding: 100px 0px;">
            <div class="heading" style="text-align: center;">
                <h3 style="color: #E1EFFD; ;font-weight: 400;">Hear from Our Successful Students</h3>
                <h6 style="color: #fff;font-weight: 300;" class="mt-4">Join thousands of students who have launched their careers with Zieltech Academy</h6>
            </div>
            <div class="container-fluid" style="margin-top: 100px;">
                <div class="owl-carousel owl-theme mt-5">
                    <div class="owl-item">
                        <div class="card">
                            <div class="testimonial mb-2">
                                <h5>Ziel’s courses helped me land a high-paying job in mobile repair!</h5>
                                <div class="testimonial-client-main">
                                    <div class="testimonial-client">
                                        <img class="img-fluid" src="{{ asset('web/upload/testimonial-img-1.webp') }}" alt="">
                                        <div class="">
                                            <p>Rajesh</p>
                                            <p>Delhi</p>
                                        </div>
                                    </div>
                                    <div class="client-rating">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="owl-item">
                        <div class="card">
                            <div class="testimonial mb-2">
                                <h5>The training was hands-on and practical. I started my own repair business.</h5>
                                <div class="testimonial-client-main">
                                    <div class="testimonial-client">
                                        <img class="img-fluid" src="{{ asset('web/upload/testimonial-img-2.webp') }}" alt="">
                                        <div class="">
                                            <p>Ankit</p>
                                            <p>Mumbai</p>
                                        </div>
                                    </div>
                                    <div class="client-rating">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="owl-item">
                        <div class="card">
                            <div class="testimonial mb-2">
                                <h5>The live classes and expert mentorship made learning so easy.</h5>
                                <div class="testimonial-client-main">
                                    <div class="testimonial-client">
                                        <img class="img-fluid" src="{{ asset('web/upload/testimonial-img-1.webp') }}" alt="">
                                        <div class="">
                                            <p>Rajesh</p>
                                            <p>Bangalore</p>
                                        </div>
                                    </div>
                                    <div class="client-rating">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="owl-item">
                        <div class="card">
                            <div class="testimonial mb-2">
                                <h5>Ziel’s courses helped me land a high-paying job in mobile repair!</h5>
                                <div class="testimonial-client-main">
                                    <div class="testimonial-client">
                                        <img class="img-fluid" src="{{ asset('web/upload/testimonial-img-2.webp') }}" alt="">
                                        <div class="">
                                            <p>Ankit</p>
                                            <p>Delhi</p>
                                        </div>
                                    </div>
                                    <div class="client-rating">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                        <img src="{{ asset('web/upload/star-icon.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
    </div>
</div>
<style>
    .gdlr-core-pbf-background{
    position: relative;
    background-position: center;
    background-size: cover;
    color: white; 
    z-index: 10;
    height: 78vh;
}
@media all and (max-width:768px) {
        .gdlr-core-pbf-background {
        height: 36vh;
    }
</style>
<script type='text/javascript' src="{{ asset('web/js/jquery/jquery.js') }}"></script>
<script type='text/javascript' src="{{ asset('web/js/jquery/jquery-migrate.min.js') }}"></script>
@endsection