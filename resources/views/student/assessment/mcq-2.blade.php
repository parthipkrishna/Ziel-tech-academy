
@extends('student.layouts.layout')
@section('student-mcq-assesment')

<div class="content py-3 px-2">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="quiz-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Technical Assessment</h3>
                <span>Question <span id="question-number">1</span>/6</span>
            </div>
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <div class="mb-3">
                <p id="question-text"></p>
            </div>
            <div class="row options" id="options"></div>
            <div class="buttons mt-3">
                <button class="prev-btn" onclick="prevQuestion()" id="prev-btn" disabled>Previous</button>
                <button class="next-btn" onclick="nextQuestion()" id="next-btn">Next</button>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- container -->
</div>
<!-- content -->          
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carousel = document.getElementById('carouselExampleIndicators');

        carousel.addEventListener('slid.bs.carousel', function (event) {
            let index = event.to; // Get current slide index

            // Hide all text sections
            document.querySelectorAll('.slider-text').forEach(text => text.classList.remove('active'));

            // Show the corresponding text
            if (index === 0) {
                document.getElementById('first-slider-text').classList.add('active');
            } else if (index === 1) {
                document.getElementById('second-slider-text').classList.add('active');
            } else if (index === 2) {
                document.getElementById('third-slider-text').classList.add('active');
            }
        });
    });

    // faq script
    const questions = [
        {
            question: "Which type of semiconductor material is most commonly used in smartphone processors?",
            options: ["assets/images/small/faqImages-1.webp", "assets/images/small/faqImages-2.webp", "assets/images/small/faqImages-3.webp", "assets/images/small/faqImages-4.webp"],
            answer: "assets/images/small/faqImages-1.webp"
        },
        {
            question: "What is the main component of a computer processor?",
            options: ["assets/images/small/faqImages-2.webp", "assets/images/small/faqImages-3.webp", "assets/images/small/faqImages-4.webp", "assets/images/small/faqImages-1.webp"],
            answer: "assets/images/small/faqImages-2.webp"
        },
        {
            question: "Which material is widely used in solar panels?",
            options: ["assets/images/small/faqImages-3.webp", "assets/images/small/faqImages-4.webp", "assets/images/small/faqImages-1.webp", "assets/images/small/faqImages-2.webp"],
            answer: "assets/images/small/faqImages-3.webp"
        },
        {
            question: "Which semiconductor material is used in high-speed electronics?",
            options: ["assets/images/small/faqImages-4.webp", "assets/images/small/faqImages-1.webp", "assets/images/small/faqImages-2.webp", "assets/images/small/faqImages-3.webp"],
            answer: "assets/images/small/faqImages-4.webp"
        },
        {
            question: "Which element is commonly used in transistors?",
            options: ["assets/images/small/faqImages-1.webp", "assets/images/small/faqImages-2.webp", "assets/images/small/faqImages-3.webp", "assets/images/small/faqImages-4.webp"],
            answer: "assets/images/small/faqImages-1.webp"
        },
        {
            question: "Which material is commonly used for LED manufacturing?",
            options: ["assets/images/small/faqImages-2.webp", "assets/images/small/faqImages-3.webp", "assets/images/small/faqImages-4.webp", "assets/images/small/faqImages-1.webp"],
            answer: "assets/images/small/faqImages-2.webp"
        }
    ];

    let currentQuestionIndex = 0;

    function loadQuestion(index) {
        document.getElementById("question-text").textContent = questions[index].question;
        document.getElementById("options").innerHTML = "";
        document.getElementById("question-number").textContent = index + 1;

        questions[index].options.forEach(option => {
            let div = document.createElement("div");
            div.classList.add("col-md-6", "text-center", "p-2");
            div.innerHTML = `<img src="${option}" alt="Option">`;
            div.onclick = () => selectOption(div);
            document.getElementById("options").appendChild(div);
        });
        document.getElementById("progress-bar").style.width = ((index + 1) / questions.length) * 100 + "%";
        document.getElementById("prev-btn").disabled = index === 0;
        document.getElementById("next-btn").textContent = index === questions.length - 1 ? "Finish" : "Next";
    }

    function selectOption(selected) {
        document.querySelectorAll(".options div").forEach(div => div.classList.remove("selected"));
        selected.classList.add("selected");
    }

    function nextQuestion() {
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            loadQuestion(currentQuestionIndex);
        }
    }

    function prevQuestion() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            loadQuestion(currentQuestionIndex);
        }
    }
    loadQuestion(currentQuestionIndex);
</script>
@endsection