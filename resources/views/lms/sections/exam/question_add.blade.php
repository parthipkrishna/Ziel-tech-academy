@extends('lms.layout.layout')
@section('add-banners')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Manage Questions for: {{ $exam->name }}</h4>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Display Existing Questions --}}
    <button id="show-question-form-btn" class="btn btn-danger mb-1"><i class="mdi mdi-plus-circle me-2"></i>
        Add 
    </button>
    {{-- Form to Add New Question --}}
    <div id="add-question-form-container" class="card mt-4 d-none">
        <div class="card-body">
            <h5 class="card-title mb-3">Add New Question</h5>
            <form action="{{ route('lms.exams.storeQuestion', $exam->id) }}" id="ExamQuestionForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="question" class="form-label">Question Text</label>
                    <textarea class="form-control" id="question" name="question" rows="3" required>{{ old('question') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mark" class="form-label">Mark</label>
                        <input type="number" class="form-control" id="mark" name="mark" value="{{ old('mark', 1) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Optional Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>

                <hr>
                <h6>Answers</h6>
                <p class="text-muted">Provide at least two answers and mark one as correct.</p>
                
                <div id="answers-container">
                    {{-- Answer Field Template (for JS and for validation errors) --}}
                    @php
                        $oldAnswers = old('answers', ['','']); // Default to two empty fields
                    @endphp
                    @foreach ($oldAnswers as $index => $oldAnswer)
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name="is_correct" value="{{ $index }}" {{ old('is_correct') == $index ? 'checked' : '' }} required title="Mark as correct">
                        </div>
                        <input type="text" class="form-control" name="answers[]" placeholder="Answer text..." value="{{ $oldAnswer }}" required>
                         @if ($index > 1) {{-- Show remove button only for dynamically added answers --}}
                            <button class="btn btn-outline-danger remove-answer-btn" type="button">Remove</button>
                         @endif
                    </div>
                    @endforeach
                </div>
                
                <button type="button" class="btn btn-secondary btn-sm" id="add-answer-btn">
                    <i class="mdi mdi-plus"></i> Add Another Answer
                </button>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Existing Questions ({{ $exam->questions->count() }})</h5>
            @forelse ($exam->questions as $index => $question)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#collapse-{{ $question->id }}"
                        role="button"
                        aria-expanded="false"
                        aria-controls="collapse-{{ $question->id }}">
                        <!-- Question -->
                        <strong>Q{{ $index + 1 }}: {{ $question->question }}</strong>
                        <!-- Right side (marks + chevron) -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">{{ $question->mark }} Marks</span>
                            <i class="mdi mdi-chevron-down"></i>
                        </div>
                    </div>       
                    <div class="collapse" id="collapse-{{ $question->id }}">
                        <ul class="list-unstyled mt-2">
                            @foreach ($question->answers as $answer)
                                <li>
                                    @if ($answer->is_correct)
                                        <i class="mdi mdi-check-circle text-success"></i> 
                                        <strong>{{ $answer->answer_text }}</strong>
                                    @else
                                        <i class="mdi mdi-circle-outline text-muted"></i>
                                        {{ $answer->answer_text }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-2">
                            <!-- Edit Button -->
                            <button class="btn btn-sm btn-outline-info edit-question-btn"
                                    data-id="{{ $question->id }}"
                                    data-question="{{ $question->question }}"
                                    data-mark="{{ $question->mark }}"
                                    data-image="{{ $question->image ? asset('storage/' . $question->image) : '' }}"
                                    data-answers='@json($question->answers)'
                                    data-bs-toggle="modal"
                                    data-bs-target="#editQuestionModal">
                                <i class="mdi mdi-square-edit-outline"></i> Edit
                            </button>
                             <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-question-modal{{ $question->id }}">
                                <i class="mdi mdi-delete"></i> Delete
                            </button> 
                        </div>
                        <!-- Delete Modal -->
                        <div id="delete-question-modal{{ $question->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="ri-information-line h1 text-info"></i>
                                            <h4 class="mt-2">Heads up!</h4>
                                            <p class="mt-3">Do you want to delete this question?</p>
                                            
                                            <form action="{{ route('lms.exam.deleteQuestion', $question->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger my-2">Delete</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No questions have been added to this exam yet.</p>
            @endforelse
        </div>
    </div>
</div>
<!-- Edit Question Modal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="EditQuestionForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="question_id" id="edit-question-id">

                    <div class="mb-3">
                        <label for="edit-question" class="form-label">Question Text</label>
                        <textarea class="form-control" id="edit-question" name="question" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-mark" class="form-label">Mark</label>
                            <input type="number" class="form-control" id="edit-mark" name="mark" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-image" class="form-label">Replace Image</label>
                            <input type="file" class="form-control" id="edit-image" name="image" accept="image/*">

                            <div id="current-image-preview" class="mt-2 d-none">
                                <p class="mb-1">Current Image:</p>
                                <img src="" id="current-image" alt="Current Question Image" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Edit Answers</h6>
                    <div id="edit-answers-container"></div>
                </div>

                <div class="modal-footer justify-content-start">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts') {{-- Or use a @section('scripts') if your layout supports it --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addAnswerBtn = document.getElementById('add-answer-btn');
    const answersContainer = document.getElementById('answers-container');
    
    // Add answer field
    addAnswerBtn.addEventListener('click', function () {
        const newIndex = answersContainer.querySelectorAll('.input-group').length;
        const newAnswerField = `
            <div class="input-group mb-3">
                <div class="input-group-text">
                    <input class="form-check-input mt-0" type="radio" name="is_correct" value="${newIndex}" required title="Mark as correct">
                </div>
                <input type="text" class="form-control" name="answers[]" placeholder="Answer text..." required>
                <button class="btn btn-outline-danger remove-answer-btn" type="button">Remove</button>
            </div>`;
        answersContainer.insertAdjacentHTML('beforeend', newAnswerField);
    });

    // Remove answer field (using event delegation)
    answersContainer.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-answer-btn')) {
            // Prevent removing if only 2 fields are left
            if (answersContainer.querySelectorAll('.input-group').length > 2) {
                e.target.closest('.input-group').remove();
            } else {
                alert('An exam question must have at least two answers.');
            }
        }
    });
});

 document.addEventListener('DOMContentLoaded', function () {
        const showFormBtn = document.getElementById('show-question-form-btn');
        const formContainer = document.getElementById('add-question-form-container');

        if (showFormBtn && formContainer) {
            showFormBtn.addEventListener('click', function () {
                formContainer.classList.toggle('d-none');
            });
        }
    });
</script>
<script>
    $(document).ready(function () {
        // Initialize form validation
        $("#ExamQuestionForm").validate({
            rules: {
                question: { required: true, minlength: 5 },
                mark: { required: true, number: true, min: 1 },
                'answers[]': { required: true, minlength: 2 }
            },
            messages: {
                question: {
                    required: "Question text is required",
                    minlength: "Question must be at least 5 characters long"
                },
                mark: {
                    required: "Mark is required",
                    number: "Mark must be a number",
                    min: "Mark must be at least 1"
                },
                'answers[]': {
                    required: "At least two answers are required",
                    minlength: "Please provide at least two answers"
                }
            },
            errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.addClass("text-danger").insertAfter(element.parent());
                    } else {
                        error.addClass("text-danger").insertAfter(element);
                    }
                },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
</script>
<script>
    $(document).on('click', '.edit-question-btn', function () {
    let questionId = $(this).data('id');
    let questionText = $(this).data('question');
    let mark = $(this).data('mark');
    let answers = $(this).data('answers');
    let imageUrl = $(this).data('image');
    
    // Set values
    $('#edit-question-id').val(questionId);
    $('#edit-question').val(questionText);
    $('#edit-mark').val(mark);

    if (imageUrl) {
    $('#current-image').attr('src', imageUrl);
    $('#current-image-preview').removeClass('d-none');
} else {
    $('#current-image-preview').addClass('d-none');
}

    // Clear existing answers
    let answersHtml = '';
    answers.forEach((answer, index) => {
        answersHtml += `
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="radio" name="is_correct" value="${index}" ${answer.is_correct ? 'checked' : ''}>
                </div>
                <input type="text" class="form-control" name="answers[]" value="${answer.answer_text}" required>
            </div>
        `;
    });

    $('#edit-answers-container').html(answersHtml);

    // Set form action dynamically
    $('#EditQuestionForm').attr('action', '{{ route("lms.exam.updateQuestion", ":id") }}'.replace(':id', questionId));

});

</script>

@endpush