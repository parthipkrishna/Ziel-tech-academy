@extends('student.layouts.layout')

@section('student-mcq-assesment')
<div class="container mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">My Exam History</h2>
        <p class="text-muted">A complete record of all your assessment attempts.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($scores->isEmpty())
                <div class="text-center p-4">
                    <i class="bi bi-journal-x fs-1 text-primary"></i>
                    <h4 class="mt-3">No Exam History Found</h4>
                    <p class="text-muted">It looks like you haven't completed any exams yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Exam Title</th>
                                <th scope="col">Score</th>
                                <th scope="col">Status</th>
                                <th scope="col">Completed On</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scores as $index => $score)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $score->exam->name }}</td>
                                    <td class="fw-bold">{{ $score->total_score }}</td>
                                    <td>
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
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($score->completed_at)->format('d M Y, h:i A') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('student.portal.history.view', $score->exam_attempt_id) }}" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye-fill me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection