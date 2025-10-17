@extends('web.layouts.layout')
@section('online-meta')
    <title>Online Mobile Repair & iPhone Technician Courses | Zieltech Academy Calicut </title>
    <meta name="description" content="Join Zieltech Academy's expert-led online mobile repair courses. Learn iPhone, smartphone, chip-level, and electronics repair with certifications. 100% online, job-oriented training in India."/>
    <meta name="keywords" content="Smartphone Repair Course Online, iPhone Repair Course Online, Mobile Engineering Course Online, Chip Level Mobile Repair, iPhone Updation Online Training, Mobile Technician Online Course, Smartphone Service Certification, Mobile Repair Training India, iOS Repair Online, Advanced Mobile Repair Course, Zieltech Online Courses, Mobile Servicing Course, Job-Oriented Mobile Training, Home-Based Repair Course, Online Electronics Repair Course">
@endsection
@section('index')
<!-- banner section -->
<section class="banner-sectionss">
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-6 banner-text">
                    <h1 data-aos="fade-left" data-aos-duration="2000">Master <span>Mobile Repair</span><br> & <span>Engineering</span> with<br> <span>Zieltech</span> or Your Career.</h1>
                    <button class="enrollBtn mt-2" onclick="location.href='#register-section'" data-aos="fade-left">Enroll Now</button>
                    <!-- <div class="dowload-btn mt-4">
                        <button><img class="img-fluid" src="{{ asset('web/upload/PlayStore-icon.svg') }}" alt=""></button>
                        <button><img class="img-fluid" src="{{ asset('web/upload/Apple-icon.svg') }}" alt=""></button>
                        <button class="dowloadNow">Download Now</button>
                    </div> -->
                </div>
                <div class="col-lg-6 banner-img" data-aos="fade-left"  >
                    <div class="enrollments">
                        <div class="d-flex">
                            <img class="img-fluid" src="{{ asset('web/upload/client-img-1.webp') }}" alt="">
                            <img class="img-fluid" src="{{ asset('web/upload/client-img-2.webp') }}" alt="">
                            <img class="img-fluid" src="{{ asset('web/upload/client-img-3.webp') }}" alt="">
                            <img class="img-fluid" src="{{ asset('web/upload/client-img-4.webp') }}" alt="">
                            <img class="img-fluid" src="{{ asset('web/upload/client-img-5.webp') }}" alt="">
                        </div>
                        <h6 class="mt-2" >1k+ enrollments</h6>
                    </div>
                    <div class="line-img">
                        <img class="img-fluid" src="{{ asset('web/upload/line-img.png') }}" alt="">
                    </div>
                    <div class="text-card">
                        <p>Learn from experts and build a thriving<br> career in mobile tech!</p>
                    </div>
                    <div class="yellow-card">
                        <img class="img-fluid" src="{{ asset('web/upload/home-intro-banner.webp') }}" alt="">
                    </div>
                    <img class="img-fluid bullets-img" src="{{ asset('web/upload/bullets-img.webp') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- zielTech section -->
    <section class="zielTech-section" data-aos="fade-up">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-4 zielTech-section-img">
                    <img class="img-fluid" src="{{ asset('web/upload/why-ziel.png') }}" alt="">
                </div>
                <div class="col-lg-8 tech-text">
                    <h2 data-aos="fade-left">Why<span> Zieltech Academy?</span></h2>
                    <!-- <div class="heading-underline"  data-aos="fade-up">
                        <div class="heading-underline1"></div>
                        <div class="heading-underline2"></div>
                    </div> -->
                    <p  data-aos="fade-left">Zieltech Academy offers expert-led courses in mobile and laptop repair, featuring hands-on training, a digital learning platform, career-focused curriculum, affordable fees, and nationwide accessibility — empowering your tech future.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- mobileRepair-section -->
    <section class="mobileRepair-section" data-aos="fade-up">
        <div class="container">
            <h2>Build Your Tech Skills at<span> Zieltech Academy</span></h2>
            <!-- <div class="heading-underline">
                <div class="heading-underline1"></div>
                <div class="heading-underline2"></div>
            </div> -->
            <div class="row">
                <div class="col-lg-4 col-md-6 mt-5" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                            <div><i class="fas fa-screwdriver-wrench" style="color: #0D4F8B; font-size: 28px;"></i></div>
                            <h3 class="mt-4">In-Demand Skills</h3>
                            <p class="mt-4">Learn to repair and troubleshoot smartphones and laptops </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                        <div><i class="fas fa-chart-line" style="color: #0D4F8B; font-size: 28px;"></i>
                        </div>
                            <h3 class="mt-4">Career Growth</h3>
                            <p class="mt-4"> Unlock job opportunities or establish your own repair business</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-5" data-aos="fade-up">
                    <div class="card">
                        <div class="card-body">
                        <div><i class="fas fa-microchip" style="color: #0D4F8B; font-size: 28px;"></i>
                        </div>
                            <h3 class="mt-4">Practical Training</h3>
                            <p class="mt-4"> Gain hands-on experience with real-world electronic repair challenges</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Learning-section -->
    <section class="learning-section" data-aos="fade-up">
        <div class="container">
            <div style="display: flex;justify-content: center;"  data-aos="fade-up">
                <div>
                    <h3>Keep Learning with <span>Zieltech Academy</span></h3>
                    <!-- <div class="heading-underline mt-3">
                        <div class="heading-underline1"></div>
                        <div class="heading-underline2"></div>
                    </div> -->
                </div>
            </div>
            <div class="timeLine">
                <div class="timeLineCard1" data-aos="fade-left" data-aos-duration="800" data-aos-delay="0">
                    <div class="number">1</div>
                    <div class="line"></div>
                    <div class="timeLineBar"></div>
                    <div class="corner-shape"></div>
                    <h6 class="mt-3">See it</h6>
                    <p>Visual learning through demonstrations and real-world examples</p>
                </div>
                <div class="timeLineCard2" data-aos="fade-left" data-aos-duration="800" data-aos-delay="300">
                    <h6 class="mt-3">Hear it</h6>
                    <p>Expert explanations and discussions to build a solid understanding</p>
                    <div class="corner-shape"></div>
                    <div class="timeLineBar"></div>
                    <div class="line"></div>
                    <div class="number">2</div>
                </div>
                <div class="timeLineCard3" data-aos="fade-left" data-aos-duration="800" data-aos-delay="600">
                    <div class="number">3</div>
                    <div class="line"></div>
                    <div class="timeLineBar"></div>
                    <div class="corner-shape"></div>
                    <h6 class="mt-3">Ask it</h6>
                    <p>Instant support and Q&A to clarify doubts and deepen insights</p>
                </div>
                <div class="timeLineCard4" data-aos="fade-left" data-aos-duration="800" data-aos-delay="900">
                    <h6 class="mt-3">Do it</h6>
                    <p>Hands-on practice to turn knowledge into skills</p>
                    <div class="corner-shape"></div>
                    <div class="timeLineBar"></div>
                    <div class="line"></div>
                    <div class="number">4</div>
                </div>
            </div>            
        </div>
    </section>

    {{-- <!-- Learning Steps Section -->
    <section class="learning-section" data-aos="fade-up">
        <div class="container-fluid">
            <div class="section-header" data-aos="fade-up">
                <h3>Keep Learning with <span>Zieltech Academy</span></h3>
            </div>

            <div class="step-box-wrapper">
                <div class="step-box arrow">
                    <div class="circle">1</div>
                    <h6>See it</h6>
                    <div class="desc">Visual learning through demonstrations and real-world examples</div>
                </div>
                <div class="step-box arrow">
                    <div class="circle">2</div>
                    <h6>Hear it</h6>
                    <div class="desc">Expert explanations and discussions to build a solid understanding</div>
                </div>
                <div class="step-box arrow">
                    <div class="circle">3</div>
                    <h6>Ask it</h6>
                    <div class="desc">Instant support and Q&A to clarify doubts and deepen insights</div>
                </div>
                <div class="step-box">
                    <div class="circle">4</div>
                    <h6>Do it</h6>
                    <div class="desc">Hands-on practice to turn knowledge into skills</div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- online training section -->
    <section class="onlineTraining-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="headings">
                    <h3>Level Up With Expert Online Training –<br> <span class="second_text">Online Training Available</span></h3>
                </div>
                <div class="container">
                    <div class="row">
                    @if ($courses->isEmpty())
                            <div class="col-12 text-center mt-5">
                                <h4>No courses found</h4>
                            </div>
                        @else
                        @foreach ($courses as $item)
                            <div class="col-lg-6 col-md-6 mt-5" data-aos="fade-up">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ env('STORAGE_URL') . '/' . $item->cover_image_web }}" class="img-fluid course-image" alt="">
                                        <div class="card-datas">
                                            <div class="d-flex gap-3">
                                                <div class="houres">
                                                    <img src="{{ asset('web/upload/time-img.webp') }}" alt="">
                                                    <p>{{ $item->total_hours }} hours</p>
                                                </div>
                                                <div class="recording">
                                                    <img src="{{ asset('web/upload/record-img.webp') }}"alt="">
                                                    <p>65 recorded videos</p>
                                                </div>
                                            </div>
                                            <div class="card-heading d-flex justify-content-between">
                                                <h6>{{ $item->name }}</h6>
                                                <h6><i class="fa-solid fa-arrow-up fa-rotate-by" style="--fa-rotate-angle: 45deg;"></i>
                                                </h6>
                                            </div>
                                                <div class="courses-datas mt-2">
                                                    <div class="box">
                                                        <p>Live Sessions + Recorded videos</p>
                                                    </div>
                                                    <div class="box">
                                                        <p>Assignments</p>
                                                    </div>
                                                    <div class="box">
                                                        <p>Certification</p>
                                                    </div>
                                                    <div class="box">
                                                        <p>Technical Assistance</p>
                                                    </div>
                                                    <div class="box">
                                                        <p>Quality Checking</p>
                                                    </div>
                                                    <div class="box">
                                                        <p>Job Placement Guarantee</p>
                                                    </div>
                                                </div>  
                                            <!-- <div class="courses-datas mt-2">
                                                @php
                                                    $tags = is_array($item->tags) ? $item->tags : explode(',', str_replace('#', '', $item->tags));
                                                @endphp

                                                @foreach($tags as $tag)
                                                    <div class="box">
                                                        <p>{{ $tag }}</p>
                                                    </div>
                                                @endforeach
                                            </div> -->
                                            <div class="totalPrice-box mt-3">
                                                <p>Tool Kit : <span>₹{{ $item->toolkit_fee }}</span></p>
                                            </div>
                                            <h4 class="mt-1">Course Fee: <span>₹{{ $item->course_fee }}</span></h4>
                                            <a href="#register-section"><button class="mt-1"> Enroll Now</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Close and start a new row after every 3 courses --}}
                            
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- visit section -->
    <section class="visitBtn-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="heading">
                    <h2>Join Our Campus for Hands-On Training</h2>
                    <button class="mt-4"><a href="{{ route('online-courses') }}">Visit our campus</a></button>
                </div>
            </div>
        </div>
    </section>
    <!-- course modules section -->
    <section class="modules-section" data-aos="fade-up" id="modulesSection">
        <div class="container">
            <div class="row">
                <h3 class="section-title">Course Modules & Details</h3>
                <div class="col-lg-5 mt-5 smartphone-img" data-aos="fade-up">
                    <img class="img-fluid" src="{{ asset('web/upload/smartphone-video.webp') }}" alt="">
                </div>
                <div class="col-lg-1 mt-5"></div>
                <div class="col-lg-6 mt-5 courseModules-datas" data-aos="fade-up">
                @foreach ($courses as $detail)
                    <div class="courseModules-datas-main">
                        <h3 class="mb-4 course-title">{{ $detail->name }}</h3>
                        @if ($detail->subjects->isNotEmpty())
                            @foreach ($detail->subjects as $subject)
                                <div class="courseModules-datas-details">
                                    <img class="img-fluid" src="{{ asset('web/upload/record-icon.png') }}" alt="">
                                    <h6 class="mt-2">{{ $subject->name }} </h6>
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
    </section>
    <!-- client review slider -->
    {{--<section class="reviewSlider-section" data-aos="fade-up">
        <div class="heading">
            <h3>Hear from Our Successful <br>Students</h3>
            <h6 class="mt-4">Join thousands of students who have<br> launched their careers with Ziel Tech<br> Academy</h6>
        </div>
        <img class="left-double-quotes" src="{{ asset('web/upload/left-double-quotes.webp') }}" alt="">
        <img class="right-double-quotes" src="{{ asset('web/upload/right-double-quotes.webp') }}" alt="">
        <div class="container-fluid">
        <div class="mt-5">
            @forelse ($testimonials as $item)
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="card">
                            <div class="testimonial mb-2">
                                <h5>{{ $item->content }}</h5>
                                <div class="testimonial-client-main">
                                    <div class="testimonial-client">
                                        <img class="img-fluid" src="{{ asset('web/upload/testimonial-img-1.webp') }}" alt="">
                                        <div class="">
                                            <p>{{ $item->student->first_name }}</p>
                                        </div>
                                    </div>
                                    <div class="client-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $item->rating)
                                                <i class="fas fa-star" style="color: #ADD8E6;"></i>
                                            @else
                                                <i class="far fa-star" style="color: #ADD8E6;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            @empty
                <p class="text-center">No testimonials available.</p>
            @endforelse
        </div>
        </div> 
    </section> --}}
    <!-- contact section -->
    <section id="register-section" class="contact-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <h4>Start Your Journey with Zieltech Academy</h4>
                <h6 class="mt-4">Fill out the form below to enroll in your desired course and start your learning journey</h6>
                <div id="register-section" class="col-lg-6 inputData mt-5" data-aos="fade-up">
                    <h2>Register <span>Now</span></h2>
                    <form class="needs-validation" id="EnrollmentForm" method="POST" action="{{ route('online.store') }}" enctype="multipart/form-data"  validate>
                        @csrf
                        <div class="form-item">
                            <input name="first_name" type="text" id="first_name" autocomplete="off" required>
                            <label>Name <span>*</span></label>
                        </div>
                        <input type="hidden" name="last_name" value="-">
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
                        <div id="errorMessages" style="color:red;"></div>
                        <button id="submitBtn" type="submit">Register Now</button>
                        <div id="successMessage" style="color: green; margin-top: 10px;"></div>                     
                    </form>
                    <div class="contact-number mt-5">
                        <div class="d-flex gap-3">
                            <img src="{{ asset('web/upload/phone-icon.webp') }}" alt="">
                            <div class="">
                                <h6>PHONE</h6>
                                <p>+918089727172</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <img src="{{ asset('web/upload/mail-icon.webp') }}" alt="">
                            <div class="">
                                <h6>EMAIL</h6>
                                <p>info@zieltechacademy.com</p>
                            </div>
                        </div>
                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#EnrollmentForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#submitBtn').attr('disabled', true); // prevent multiple clicks
            $('#errorMessages').html(''); // clear previous errors

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
                        // Validation errors
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul>';
                        $('#errorMessages').html(errorHtml);
                    } else if(xhr.responseJSON?.message) {
                        // Custom error message
                        $('#errorMessages').html('<p>' + xhr.responseJSON.message + '</p>');
                    } else {
                        $('#errorMessages').html('<p>Something went wrong. Please try again.</p>');
                    }
                }
            });
        });
    </script>
    @endsection