@extends('student.layouts.layout')
@section('student-mcq-assesment')
<div class="content pt-4 px-3">
    <div class="container-fluid">
        @if ($exams->isEmpty())
            <div class="alert alert-info text-center shadow-sm rounded">
                <h4><i class="bi bi-info-circle"></i> No Exams Available</h4>
                <p>You have completed all available Exams or no Exams are assigned.</p>
            </div>
        @else
            <h3 class="text-center mb-5 fw-bold text-primary">📚 Available Exams</h3>
            <div class="row">
                @foreach ($exams as $exam)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0 hover-shadow transition">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $exam['title'] }}</h5>
                                <p class="card-text" style="color: #4f5153ff;">
                                    <i class="bi bi-journal-text me-2"></i><strong>Subject:</strong> {{ $exam['subject'] }}<br>
                                    <i class="bi bi-clock me-2"></i><strong>Duration:</strong> {{ $exam['duration'] }} mins<br>
                                    <i class="bi bi-question-circle me-2"></i><strong>Questions:</strong> {{ $exam['question_count'] }}
                                </p>

                                @if ($exam['is_locked'])
                                    <button class="btn btn-outline-secondary w-100 fw-bold" disabled>
                                        <i class="bi bi-lock me-2"></i>Locked
                                    </button>
                                @else
                                    <a href="{{ route('student.exam.show', $exam['id']) }}" class="btn btn-outline-success w-100 fw-bold">
                                        <i class="bi bi-play-circle me-2"></i>Start Exam
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
