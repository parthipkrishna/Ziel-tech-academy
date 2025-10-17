@extends('student.layouts.layout')

@section('student-mcq-assesment')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Exam Result</h2>
            <p class="text-muted mb-0">Review for: {{ $score->exam->name }}</p>
        </div>
        <a href="{{route('student.portal.history.index')}}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to History
        </a>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-footer text-center bg-light">
            <strong>Status:</strong>
            @if ($score->attempt)
                <span class="badge 
                    @if($score->attempt->status == 'Passed') bg-success 
                    @elseif($score->attempt->status == 'Failed') bg-danger 
                    @else bg-warning @endif">
                    {{ $score->attempt->status }}
                </span>
            @else
                <span class="badge bg-secondary">No Attempt Data</span>
            @endif
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row text-center gy-4 ms-5">
                {{-- Score section --}}
                <div class="col-md-2 col-6">
                    <i class="bi bi-bar-chart-line-fill fs-2 text-primary"></i>
                    <h5 class="mt-2 mb-0 fw-bold">{{ $score->total_score }}</h5>
                    <small class="text-muted">Your Total Score</small>
                </div>
                <div class="col-md-2 col-6">
                    <i class="bi bi-file-earmark-text-fill fs-2 text-info"></i>
                    <h5 class="mt-2 mb-0 fw-bold">{{ $score->exam->total_marks ?? '-' }}</h5>
                    <small class="text-muted">Total Marks</small>
                </div>
                {{-- Correct Answers section --}}
                <div class="col-md-2 col-6">
                    <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                    <h5 class="mt-2 mb-0 fw-bold">{{ $score->correct_answers }}</h5>
                    <small class="text-muted">Correct</small>
                </div>
                {{-- Incorrect Answers section --}}
                <div class="col-md-2 col-6">
                    <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
                    <h5 class="mt-2 mb-0 fw-bold">{{ $score->incorrect_answers }}</h5>
                    <small class="text-muted">Incorrect</small>
                </div>
                {{-- Time Taken section --}}
                <div class="col-md-2 col-6">
                    <i class="bi bi-clock-history fs-2 text-info"></i>
                    <h5 class="mt-2 mb-0 fw-bold">{{ $score->total_time_taken }}</h5>
                    <small class="text-muted">Time Taken</small>
                </div>
            </div>
        </div>
    </div>  
    <h3 class="mb-3">Question Review</h3>
    @foreach ($score->exam->questions as $q)
        @php
            $studentAnswer = $score->answers->firstWhere('question_id', $q->id);
        @endphp
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <p class="mb-0 fw-bold">Question {{ $loop->iteration }}: {{ $q->question }}</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($q->answers as $a)
                    @php
                        $isCorrectAnswer = $a->is_correct;
                        $isStudentSelection = $studentAnswer && $studentAnswer->selected_answer_id == $a->id;
                        
                        $itemClass = '';
                        if ($isCorrectAnswer) {
                            $itemClass = 'list-group-item-success'; 
                        } elseif ($isStudentSelection && !$isCorrectAnswer) {
                            $itemClass = 'list-group-item-danger'; 
                        }
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center {{ $itemClass }}">
                        <span>{{ $a->answer_text }}</span>
                        
                        @if ($isCorrectAnswer)
                            <span class="badge text-bg-success"><i class="bi bi-check-circle-fill me-1"></i> Correct Answer</span>
                        @endif
                        @if ($isStudentSelection && !$isCorrectAnswer)
                            <span class="badge text-bg-danger"><i class="bi bi-x-circle-fill me-1"></i> Your Answer</span>
                        @endif
                        @if ($isStudentSelection && $isCorrectAnswer)
                             <span class="badge text-bg-primary"><i class="bi bi-check-circle-fill me-1"></i> Your Answer</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            @if($q->explanation)
                <div class="card-footer text-muted small">
                    <strong>Explanation:</strong> {{ $q->explanation }}
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection