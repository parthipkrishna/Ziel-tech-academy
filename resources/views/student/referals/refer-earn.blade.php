  
@extends('student.layouts.layout')
@section('student-referal')

<div class="content">
    <!-- Start Content-->
    <div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 100vh;">

        <!-- Referral Section -->
        <div class="refer-section p-4 rounded text-center mt-2" style="background-color: #f1f6fd; width: 100%; max-width: 600px;">
            <img src="{{ asset('student/assets/images/small/refer-img.png') }}" class="promo-image img-fluid" alt="Refer Illustration">
            <p class="promo-text">Refer a friend & Get 50% off</p>
            <p class="promo-text2">You get 50% off on your friend’s first subscription</p>

            <!-- Referral Code -->
            <div class="input-group mb-3">
                <input type="text" class="referral-input" value="{{ $referralCode->code ?? '' }}" readonly>
                <button class="btn copy-btn" onclick="copyCode()">
                    <i class="ri-file-copy-2-line me-1"></i>Copy
                </button>
            </div>
            @unless($referralCode?->code)
                <small class="text-muted d-block mb-3">No Referral Code Available</small>
            @endunless

            <!-- Social Share Buttons -->
            <div class="social-icons justify-content-center align-items-center" >
                <button onclick="shareChat()">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Chat</span>
                </button>
                <button onclick="shareTelegram()">
                    <i class="bi bi-telegram"></i>
                    <span>Telegram</span>
                </button>
                <button onclick="shareWhatsApp()">
                    <i class="bi bi-whatsapp"></i>
                    <span>WhatsApp</span>
                </button>
                <button onclick="shareMore()">
                    <i class="bi bi-share-fill"></i>
                    <span>More</span>
                </button>
            </div>
        </div>

    </div>
</div>


<script>
    const referralCode = "{{ $referralCode->code ?? '' }}";
    const referralMessage = `Hey! Use my referral code ${referralCode} and get 50% off on your first subscription!`;

    function copyCode() {
        const input = document.querySelector(".referral-input");
        input.select();
        input.setSelectionRange(0, 99999); // For mobile
        document.execCommand("copy");
        alert("Referral Code Copied!");
    }

    function shareWhatsApp() {
        const url = `https://wa.me/?text=${encodeURIComponent(referralMessage)}`;
        window.open(url, '_blank');
    }

    function shareTelegram() {
        const url = `https://t.me/share/url?url=&text=${encodeURIComponent(referralMessage)}`;
        window.open(url, '_blank');
    }

    function shareChat() {
        const url = `sms:?&body=${encodeURIComponent(referralMessage)}`;
        window.open(url, '_blank');
    }

    function shareMore() {
        if (navigator.share) {
            navigator.share({
                title: 'Refer a Friend',
                text: referralMessage,
                url: window.location.href
            }).catch(console.error);
        } else {
            alert("Sharing not supported on this browser.");
        }
    }
</script>
<style>
    .refer-section {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        text-align: left;
    }

    .promo-image {
        width: 100%;
        max-width: 100%;
        margin: 0 auto 15px;
        display: block;
    }

    .promo-text {
        color: #0F3E6B;
        font-size: 24.82px;
        font-weight: 500;
        margin-top: 20px;
        margin-bottom: 0px !important;
    }

    .promo-text2 {
        color: #0F3E6B;
        font-size: 15.60px;
        font-weight: 400;
    }

    .input-group {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
        width: 100%;
    }

    .referral-input {
        text-align: left;
        font-weight: 400;
        border-radius: 10px !important;
        border: 0.71px #0F3E6B solid !important;
        background-color: transparent;
        width: 70%;
        padding: 8px 15px;
        color: #0F3E6B;
        letter-spacing: 3.30px;
        font-size: 21.98px;
    }

    .copy-btn {
        background-color: #0F3E6B;
        color: white;
        border-radius: 10px !important;
        width: 26%;
        font-size: 18px;
        font-weight: 500;
    }

    .copy-btn:hover {
        background-color: #0F3E6B !important;
        color: white !important;
    }

    .social-icons {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .social-icons button {
        border: none;
        background: none;
        font-size: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #555;
        cursor: pointer;
    }

    .social-icons button i {
        font-size: 20px;
        margin-bottom: 5px;
        color: #3D5A80 !important;
        height: 50px;
        width: 50px;
        border-radius: 50px;
        background: rgba(15, 62, 107, 0.04) !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .social-icons button:hover i {
        color: #003399;
    }
</style>


    @endsection