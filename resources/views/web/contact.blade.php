@extends('web.layouts.layout')
@section('contact')
<!-- navbar end -->
    <div class="kingster-page-wrapper" style="margin-top: 0px;" id="kingster-page-wrapper">
        <div class="gdlr-core-page-builder-body">
        @if(count($banner) > 0)
        @foreach ($banner as $item)
            <section class="banner-sections" 
                style="background-image: url('{{ $item->image_url ? Storage::url($item->image_url) : asset('web/upload/Contact_banner.webp') }}');">
                <div class="container">
                    <div class="row" data-aos="fade-up">
                        <h1>{{ $item->title}}</h1>
                        <h6>{{ $item->short_desc}}</h6>
                    </div>
                </div>
            </section>
        @endforeach
        @else
            <section class="banner-sections" style="background-image: url('{{ asset('web/upload/Contact_banner.webp') }}');">
                <div class="container">
                    <div class="row" data-aos="fade-up">
                        <h1>Contact Us</h1>
                        <h6>Get In Touch</h6>
                    </div>
                </div>
            </section>
        @endif
        <div class="gdlr-core-pbf-wrapper " style="padding: 100px 0px 40px 0px;" data-skin="White Text">
            <div class="gdlr-core-pbf-background-wrap" style="background-color: #F6FBFF ;"></div> 
            <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
                <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
                    <div class="gdlr-core-pbf-element">
                        <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-center-align gdlr-core-title-item-caption-bottom gdlr-core-item-pdlr" style="padding-bottom: 60px ;">
                            <div class="gdlr-core-title-item-title-wrap clearfix">
                                <h3 class="gdlr-core-title-item-title gdlr-core-skin-title " style="font-size: 39px;font-weight: 400;  background: linear-gradient(90deg, #E5B92299 16%, #0D4F8B 99%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Get in Touch</h3></div><span class="gdlr-core-title-item-caption gdlr-core-info-font gdlr-core-skin-caption" style="font-size: 19px ;font-style: normal ;text-transform: uppercase ; color: #0D4F8B;">Join us at Zieltech Academy and take your skills to the next level!</span></div>
                        </div>
                    <div  class="gdlr-core-pbf-column gdlr-core-column-30 gdlr-core-column-first">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js " style="padding: 20px 20px 0px 20px;">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js inputData" data-gdlr-animation="fadeInUp" data-gdlr-animation-duration="600ms" data-gdlr-animation-offset="0.8">
                                <h2 style="background: linear-gradient(90deg, #E5B92299 6%, #0D4F8B 95%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"> <span style="background: linear-gradient(90deg, #E5B92299 6%, #0D4F8B 95%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; ">Enquire Now</span></h2>
                                <form>
                                    <div class="form-item">
                                        <input type="text" id="name" autocomplete="off" required>
                                        <label>Name <span>*</span></label>
                                    </div>
                                    <div class="form-item">
                                        <input type="email" id="email" autocomplete="off" required>
                                        <label>Email <span>*</span></label>
                                    </div>
                                    <div class="form-item">
                                        <input type="text" id="number" autocomplete="off" required>
                                        <label>Phone number <span>*</span></label>
                                    </div> 
                                    <div class="form-item">
                                        <textarea rows="4" cols="50" name="comment" form="usrform" placeholder="Message"></textarea>
                                    </div> 
                                    <button>Send</button>                     
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-30">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js " style="padding: 20px 20px 0px 20px;">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js  contact-img" data-gdlr-animation="fadeInDown" data-gdlr-animation-duration="600ms" data-gdlr-animation-offset="0.8">
                                <img src="{{ asset('web/upload/contact-img.webp') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gdlr-core-pbf-wrapper-content gdlr-core-js ">
                <div class="gdlr-core-pbf-wrapper-container clearfix gdlr-core-container">
                    <div class="gdlr-core-pbf-column gdlr-core-column-20 gdlr-core-column-first">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js " style="padding: 100px 20px 0px 20px;">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js " data-gdlr-animation="fadeInUp" data-gdlr-animation-duration="600ms" data-gdlr-animation-offset="0.8">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-icon-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 35px ;"><i class=" gdlr-core-icon-item-icon fa fa-phone" style="color: #0D4F8B ;font-size: 40px ;min-width: 40px ;min-height: 40px ;"></i></div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-top gdlr-core-item-pdlr" style="padding-bottom: 25px ;">
                                        <div class="gdlr-core-title-item-title-wrap clearfix">
                                            <h3 class="gdlr-core-title-item-title gdlr-core-skin-title " style="font-size: 26px ;letter-spacing: 0px ;text-transform: none ;">Phone</h3></div>
                                    </div>
                                </div>
                                @if ($contact)
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 0px ;">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ;">
                                        </div>
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px; color:#0D4F8B;">
                                        <p>Need assistance? Call us anytime for support and inquiries</p>
                                        </div>
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ;text-transform: none ;color: #3db166 ;">
                                        <p><a href="tel:{{ $contact->phone }}" style="color: #0D4F8B;">{{ $contact->phone }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-20">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js " style="padding: 100px 20px 0px 20px;">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js " data-gdlr-animation="fadeInDown" data-gdlr-animation-duration="600ms" data-gdlr-animation-offset="0.8">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-icon-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 35px ;"><i class=" gdlr-core-icon-item-icon fa fa-envelope-o" style="color: #0D4F8B ;font-size: 40px ;min-width: 40px ;min-height: 40px ;"></i></div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-top gdlr-core-item-pdlr" style="padding-bottom: 25px ;">
                                        <div class="gdlr-core-title-item-title-wrap clearfix">
                                            <h3 class="gdlr-core-title-item-title gdlr-core-skin-title " style="font-size: 26px ;letter-spacing: 0px ;text-transform: none ;">Email</h3></div>
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 0px ;">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ;">
                                        </div>
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ; color:#0D4F8B;">
                                        <p>Subscribe to our newsletter and stay updated with the latest news, exclusive offers, and insights.</p>
                                        </div>
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ;">
                                        <p><a href="mailto:{{ $contact->email }}" style="color: 0D4F8B;">{{ $contact->email }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gdlr-core-pbf-column gdlr-core-column-20">
                        <div class="gdlr-core-pbf-column-content-margin gdlr-core-js " style="padding: 100px 20px 0px 20px;">
                            <div class="gdlr-core-pbf-column-content clearfix gdlr-core-js " data-gdlr-animation="fadeInUp" data-gdlr-animation-duration="600ms" data-gdlr-animation-offset="0.8">
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-icon-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 35px ;"><i class=" gdlr-core-icon-item-icon fa fa-location-arrow" style="color: #0D4F8B ;font-size: 40px ;min-width: 40px ;min-height: 40px ;"></i></div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-title-item gdlr-core-item-pdb clearfix  gdlr-core-left-align gdlr-core-title-item-caption-top gdlr-core-item-pdlr" style="padding-bottom: 25px ;">
                                        <div class="gdlr-core-title-item-title-wrap clearfix">
                                            <h3 class="gdlr-core-title-item-title gdlr-core-skin-title " style="font-size: 26px ;letter-spacing: 0px ;text-transform: none ;">Location</h3></div>
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align" style="padding-bottom: 0px ;">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ; color:#0D4F8B;">
                                            <p>{{ $contact->address }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="gdlr-core-pbf-element">
                                    <div class="gdlr-core-text-box-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-left-align">
                                        <div class="gdlr-core-text-box-item-content" style="font-size: 16px ;">
                                            <p><a href="{{ $contact->google_map_link }}">View On Google Map</a></p>
                                        </div>
                                        @else
                                            <p style="color:0D4F8B;">No contact information available.</p>
                                        @endif
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
</style>

@endsection