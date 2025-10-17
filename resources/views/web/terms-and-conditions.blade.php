@extends('web.layouts.layout')

@section('terms-and-conditions-meta')
    <title>Terms and Conditions - Ziel Tech Academy Calicut </title>
    <meta name="description" content="Read the Terms and Conditions for using Ziel Tech Academy’s mobile platform and services."/>
    <style>
        .terms-container {
            color: #E1EFFD;
            font-size: 19px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 400;
            line-height: 28.90px;
            margin-top: 60px;
            margin-bottom: 100px;
        }
        .container {
            max-width: 72rem;
        }
        h1, h2 {
            /* color: #0D4F8B; */
        }
        .banner-sections-term {
            position: relative;
            background-position: center;
            background-size: cover;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 1;
            margin-top: 100px;
        }
        .banner-sections-term::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 50vh;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }
        .banner-sections-term h1 {
            font-size: 75px;
            font-weight: 700;
            letter-spacing: 0px;
            color: #ffffff;
            text-align: center;
        }
    </style>
@endsection 

@section('terms-and-conditions')
    <section class="banner-sections-term" style="background-image: url('{{ asset('web/upload/Terms.png') }}');">
            <div class="container">
                <div class="row" data-aos="fade-left">
                    <h1>Terms & Conditions</h1>
                </div>
            </div>
    </section>

    <div class="container terms-container">
        <h1 class="mb-4">Terms and Conditions</h1>
        <p>These Terms and Conditions govern your use of Zieltech Academy's website, mobile application, and other services. By accessing our platforms or enrolling in our courses, you agree to abide by these terms.</p>
        
        <h2 class="mt-4">1. Services Offered</h2>
        <p>Zieltech Academy provides technical training in the following areas:</p>
        <ul>
            <li>Smartphone Engineering</li>
            <li>Laptop Engineering</li>
        </ul>
        <p>Courses are available via our LMS mobile application, website, and through guided in-person or remote instruction.</p>
        
        <h2 class="mt-4">2. User Responsibilities</h2>
        <p>When using our platform, you agree to:</p>
        <ul>
            <li>Provide accurate personal information during registration</li>
            <li>Use the platform lawfully and responsibly</li>
            <li>Not share your login details or course materials with others</li>
            <li>Respect all intellectual property rights of the academy</li>
        </ul>

        <h2 class="mt-4">3. Payments and Refunds</h2>
        <p>Course fees must be paid in full at the time of enrollment. All payments are non-refundable unless specified in a written policy or under exceptional circumstances approved by the academy’s management.</p>

        <h2 class="mt-4">4. Course Access & LMS Usage</h2>
        <p>Students will receive access to Zieltech Academy's Learning Management System (LMS). Access is granted only for the duration of the course and must not be shared or resold.</p>
        <p>Misuse of the platform may result in suspension or termination of access without refund.</p>

        <h2 class="mt-4">5. Termination of Access</h2>
        <p>Zieltech Academy reserves the right to suspend or terminate a user's account and access to services without prior notice in the case of a breach of these Terms.</p>

        <h2 class="mt-4">6. Intellectual Property</h2>
        <p>All content, course materials, videos, graphics, logos, and branding are the intellectual property of Zieltech Academy and may not be copied, distributed, or reused without written permission.</p>

        <h2 class="mt-4">7. Limitation of Liability</h2>
        <p>Zieltech Academy is not responsible for any indirect, incidental, or consequential damages arising from the use of our services. We provide education and training; success outcomes depend on individual performance.</p>

        <h2 class="mt-4">8. Updates to Terms</h2>
        <p>We may update or change these Terms & Conditions at any time. Updates will be posted on our website or notified via email/WhatsApp as necessary. Continued use after updates implies agreement to the new terms.</p>

        <h2 class="mt-4">9. Contact Information</h2>
        <p>If you have questions or concerns regarding these Terms and Conditions, contact us:</p>
        <ul>
            <li><strong>Email:</strong> info@zieltechacademy.com</li>
            <li><strong>Phone/WhatsApp:</strong> +918089727172 </li>
            <li><strong>Website:</strong> <a href="https://www.zieltechacademy.com" target="_blank">www.zieltechacademy.com</a></li>
        </ul>
    </div>

@endsection