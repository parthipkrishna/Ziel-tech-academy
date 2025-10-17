@extends('student.layouts.layout')
@section('student-mcq-assesment')
<div class="content pt-3 px-2">
    <div class="container-fluid">
        @if ($questions->isEmpty())
            <div class="alert alert-info text-center">
                <h4>No Questions Available</h4>
                <p>This assessment has no questions or has already been completed.</p>
            </div>
        @else
            <!-- Timer Display -->
            <div id="exam-timer" class="text-center mb-3" style="font-weight:bold; font-size:18px; color:red; display:none;">
                Time Remaining: <span id="time-remaining"></span>
            </div>
            <div id="submission-message" class="alert alert-success mt-3 text-center" style="display: none;"></div>
            <!-- Start Screen -->
            <div id="start-screen" class="text-center">
                <h3>Welcome to {{ $exam->title ?? 'Technical Assessment' }}</h3>
                <p>Click the button below to begin your assessment.</p>
                <button class="next-btn" onclick="startAssessment()" id="start-btn">Start</button>
            </div>
            <!-- Quiz Container (hidden initially) -->
            <div class="quiz-container" id="quiz-container" style="display:none;">
                <div class="question-header">
                    <h3>{{ $exam->title ?? 'Technical Exam' }}</h3>
                    <span>Question <span id="question-number">1</span> / {{ count($questions) }}</span>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" id="progress-bar"></div>
                </div>
                <div class="question-container">
                    <p id="question-text"></p>
                    <ul class="options" id="options"></ul>
                </div>
                <div class="buttons">
                    <button class="prev-btn" onclick="prevQuestion()" id="prev-btn" disabled>Previous</button>
                    <button class="next-btn" onclick="nextQuestion()" id="next-btn">Next</button>
                </div>
                <!-- Leave Exam Button fixed bottom center -->
                <div style="position: absolute; bottom: 70px; left: 50%; transform: translateX(-50%);">
                    <button class="prev-btn" onclick="leaveExam()" id="leave-btn">Leave Exam</button>
                </div>
            </div>
            <script>
                const questions = @json($questions);
                let currentQuestionIndex = 0;
                let userAnswers = Array(questions.length).fill(null);

                function startAssessment() {
                    let examId = "{{ $exam->id }}";
                    let studentId = "{{ $studentId }}";
                    let durationMinutes = "{{ $exam->duration ?? 0 }}";
                    let durationMs = durationMinutes * 60 * 1000;

                    // Show timer
                    document.getElementById("exam-timer").style.display = "block";

                    // Countdown
                    let endTime = Date.now() + durationMs;
                    let timerInterval = setInterval(() => {
                        let remaining = endTime - Date.now();
                        if (remaining <= 0) {
                            clearInterval(timerInterval);
                            document.getElementById("time-remaining").textContent = "00:00";
                            submitAnswers();
                        } else {
                            let minutes = Math.floor(remaining / 1000 / 60);
                            let seconds = Math.floor((remaining / 1000) % 60);
                            document.getElementById("time-remaining").textContent =
                                `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
                        }
                    }, 1000);

                    // Log exam attempt
                    fetch("{{ route('student.exam.log') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            exam_id: examId,
                            student_id: studentId,
                            status: "Join"
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            console.log("Exam joined:", data);
                            if (data.attempt_id) {
                                localStorage.setItem("exam_attempt_id", data.attempt_id);
                            }
                            document.getElementById("start-screen").style.display = "none";
                            document.getElementById("quiz-container").style.display = "block";
                            loadQuestion(currentQuestionIndex);
                        } else {
                            alert(data.message || "Failed to start exam.");
                        }
                    })
                    .catch(err => {
                        console.error("Join error:", err);
                        alert("Something went wrong while starting exam.");
                    });
                }

                function loadQuestion(index) {
                    const q = questions[index];
                    document.getElementById("question-text").textContent = q.question;
                    const optionsContainer = document.getElementById("options");
                    optionsContainer.innerHTML = "";

                    if (q.image) {
                        let img = document.createElement("img");
                        img.src = q.image;
                        img.alt = "Question Image";
                        img.style.maxWidth = "300px";
                        img.classList.add("mb-3", "d-block", "mx-auto");
                        optionsContainer.appendChild(img);
                    }

                    q.options.forEach(option => {
                        let li = document.createElement("li");
                        li.textContent = option.text;
                        li.dataset.answerId = option.id;
                        li.onclick = () => selectOption(li);
                        if (userAnswers[index] == option.id) {
                            li.classList.add("selected");
                        }
                        optionsContainer.appendChild(li);
                    });

                    document.getElementById("question-number").textContent = index + 1;
                    document.getElementById("progress-bar").style.width =
                        ((index + 1) / questions.length) * 100 + "%";
                    document.getElementById("prev-btn").disabled = index === 0;
                    document.getElementById("next-btn").textContent =
                        index === questions.length - 1 ? "Finish" : "Next";
                }

                function selectOption(selected) {
                    document.querySelectorAll(".options li").forEach(li => li.classList.remove("selected"));
                    selected.classList.add("selected");
                    const q = questions[currentQuestionIndex];
                    userAnswers[currentQuestionIndex] = parseInt(selected.dataset.answerId);

                    let examId = "{{ $exam->id }}";
                    let studentId = "{{ $studentId }}";
                    let examAttemptId = localStorage.getItem("exam_attempt_id");

                    fetch("{{ route('student.exam.answer.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            exam_id: examId,
                            student_id: studentId,
                            exam_attempt_id: examAttemptId,
                            question_id: q.id,
                            selected_answer_id: selected.dataset.answerId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.status) {
                            console.error("Answer not saved:", data.message);
                        } else {
                            console.log("Answer saved:", data.message);
                        }
                    })
                    .catch(err => {
                        console.error("Error saving answer:", err);
                    });
                }

                function nextQuestion() {
                    if (currentQuestionIndex < questions.length - 1) {
                        currentQuestionIndex++;
                        loadQuestion(currentQuestionIndex);
                    } else {
                        submitAnswers();
                    }
                }

                function prevQuestion() {
                    if (currentQuestionIndex > 0) {
                        currentQuestionIndex--;
                        loadQuestion(currentQuestionIndex);
                    }
                }

                function submitAnswers() {
                    let examId = "{{ $exam->id }}";
                    let studentId = "{{ $studentId }}";
                    let examAttemptId = localStorage.getItem("exam_attempt_id");

                    if (!examAttemptId) {
                        alert("Exam attempt not found. Please restart exam.");
                        return;
                    }

                    const results = questions.map((q, index) => ({
                        question: q.question,
                        selected: userAnswers[index],
                        correct: q.answer,
                        mark: q.mark
                    }));
                    let totalScore = 0;
                    results.forEach(ans => {
                        if (parseInt(ans.selected) === parseInt(ans.correct)) {
                            totalScore += ans.mark;
                        }
                    });
                    const correctCount = results.filter(ans => parseInt(ans.selected) === parseInt(ans.correct)).length;
                    const incorrectCount = results.length - correctCount;

                    fetch("{{ route('student.exam.assessment.submit') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            exam_id: examId,
                            student_id: studentId,
                            exam_attempt_id: examAttemptId,
                            correct_answers: correctCount,
                            incorrect_answers: incorrectCount,
                            total_score: totalScore
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            document.getElementById("modal-success-message").textContent = data.message || "Exam finished successfully!";
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            setTimeout(() => {
                                window.location.href = "{{ route('student.exam.assessment') }}";
                            }, 1000);
                        } else {
                            document.getElementById("modal-error-list").textContent = data.message || "Failed to submit exam.";
                            let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        }
                    })
                    .catch(err => {
                        console.error("Submission error:", err);
                        document.getElementById("modal-error-list").textContent = "Something went wrong while submitting exam.";
                        let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                        errorModal.show();
                    });
                }

                function leaveExam() {
                    let confirmModal = new bootstrap.Modal(document.getElementById('confirm-leave-modal'));
                    confirmModal.show();

                    document.getElementById("confirm-leave-btn").addEventListener("click", function() {
                        confirmModal.hide();
                        let examId = "{{ $exam->id }}";
                        let studentId = "{{ $studentId }}";
                        let examAttemptId = localStorage.getItem("exam_attempt_id");

                        fetch("{{ route('student.exam.log') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                exam_id: examId,
                                student_id: studentId,
                                exam_attempt_id: examAttemptId,
                                status: "Left"
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status) {
                                window.location.href = "{{ route('student.exam.assessment') }}";
                            } else {
                                document.getElementById("modal-error-list").textContent = data.message || "Failed to update status.";
                                let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                                errorModal.show();
                            }
                        })
                        .catch(err => {
                            console.error("Leave error:", err);
                            document.getElementById("modal-error-list").textContent = "Something went wrong while leaving exam.";
                            let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        });
                    }, { once: true });
                }
            </script>
        @endif
    </div>
</div>
<!-- Leave Exam Confirmation Modal -->
<div class="modal fade" id="confirm-leave-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Leave Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to leave the exam?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-leave-btn">Yes, Leave</button>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="success-alert-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modal-success-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Error Modal -->
<div class="modal fade" id="danger-alert-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modal-error-list"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<style>
    .quiz-container {
        width: 100%;
        padding: 20px 30px 20px 30px;
        border-radius: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }
    .progress-container {
        width: 100%;
        background-color: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .progress-bar {
        width: 0%;
        height: 6px;
        background-color: #80C4F9;
        transition: width 0.3s ease-in-out;
    }
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .question-container {
        margin-bottom: 20px;
    }
    .options {
        list-style: none;
        padding: 0;
    }
    .options li {
        padding: 15px;
        border: 1px solid #ccc;
        margin: 10px 0;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    .options li::after {
        content: '\2713';
        font-size: 18px;
        width: 24px;
        height: 24px;
        line-height: 24px;
        text-align: center;
        border-radius: 50%;
        background-color: #ddd;
        color: transparent;
        display: inline-block;
        transition: 0.3s;
    }
    .options li:hover,
    .options li.selected {
        background: var(--Congress-Blue-100, #E1EFFD);
        border-color: var(--Black-Pearl-800, #004D95);
    }
    .options li.selected::after {
        background-color: #004D95;
        color: white;
    }
    .buttons {
        display: flex;
        justify-content: space-between;
    }
    .prev-btn,
    .next-btn {
        height: 45px;
        width: 180px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
    }
    .prev-btn {
        border: 1px var(--Black-Pearl-700, #005AB4) solid;
        color: var(--Black-Pearl-950, #001021);
    }
    .next-btn {
        background: var(--Congress-Blue-800, #0B4E89);
        color: white;
    }
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    @media screen and (max-width:768px) {
        .quiz-container {
            padding: 20px;
        }
        .prev-btn,
        .next-btn {
            height: 40px;
            width: 100px;
            font-size: 10px;
        }
    }
</style>
@endsection