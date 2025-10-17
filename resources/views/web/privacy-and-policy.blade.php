@extends('web.layouts.layout')

@section('privacy-and-policy-meta')
    <title>Privacy Policy - Ziel Tech Academy Calicut </title>
    <meta name="description" content="Understand how Ziel Tech Academy collects, uses, and protects your personal information."/>

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

@section('privacy-and-policy')

    <section class="banner-sections-term" style="background-image: url('{{ asset('web/upload/Privacy.png') }}');">
            <div class="container">
                <div class="row" data-aos="fade-left">
                    <h1>Privacy Policy</h1>
                </div>
            </div>
    </section>

    <div class="container terms-container">
        <h1 class="mb-4">Privacy Policy</h1>
        <p>At Zieltech Academy, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information when you use our website, mobile application, or connect with us through WhatsApp.</p>

        <h2 class="mt-4">Personal Information We Collect</h2>
        <p>We may collect the following personal information from students and users:</p>
        <ul>
            <li>Full Name</li>
            <li>Age</li>
            <li>Contact Number</li>
            <li>Email Address</li>
            <li>Course Details (e.g., course name, enrollment date)</li>
            <li>Device information such as browser type, IP address, time zone, and cookies</li>
        </ul>

        <h2 class="mt-4">How We Collect Information</h2>
        <p>We collect information when you:</p>
        <ul>
            <li>Register for a course</li>
            <li>Browse the website or mobile app</li>
            <li>Submit a contact form</li>
            <li>Communicate with us via WhatsApp</li>
        </ul>

        <h2 class="mt-4">Why We Process Your Data</h2>
        <p>Your information is used to:</p>
        <ul>
            <li>Manage course enrollment</li>
            <li>Deliver course content via LMS</li>
            <li>Send important notifications</li>
            <li>Improve our services and platform</li>
            <li>Ensure legal and regulatory compliance</li>
        </ul>

        <h2 class="mt-4">Data Security</h2>
        <p>We implement secure servers and technical safeguards to protect your data. However, no internet transmission is entirely secure.</p>

        <h2 class="mt-4">Your Rights (GDPR)</h2>
        <p>European users have the right to access, update, delete, or restrict the processing of their personal data. To exercise these rights, contact us at <strong>info@zieltechacademy.com</strong>.</p>

        <h2 class="mt-4">Third-Party Links</h2>
        <p>We are not responsible for privacy policies of third-party websites linked from our platform.</p>

        <h2 class="mt-4">Legal Disclosure</h2>
        <p>We may disclose your information if required by law or to protect the rights and safety of others.</p>

        <h2 class="mt-4">Contact Us</h2>
        <p>If you have questions about this policy, contact us:</p>
        <ul>
            <li><strong>Email:</strong> info@zieltechacademy.com</li>
            <li><strong>Website:</strong> <a href="https://www.zieltechacademy.com" target="_blank">www.zieltechacademy.com</a></li>
        </ul>
    </div>

@endsection