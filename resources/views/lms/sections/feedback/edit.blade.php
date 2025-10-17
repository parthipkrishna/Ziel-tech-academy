@extends('lms.layout.layout')
@section('edit-student-feedback')
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Student Feedbacks</a></li>
                        <li class="breadcrumb-item active">Student Feedback Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information  -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card" style="height: 13rem;">
                <div class="card-body">
                    <h4 class="header-title mb-3">Student Personal Information</h4>
                    <h5>{{ $student->first_name }} {{ $student->last_name }}</h5>
                    <address class="mb-0 font-14 address-lg">
                        {{ $student->address }}<br>
                        {{ $student->city }}, {{ $student->state }}, {{ $student->country }}<br>
                        <span class="fw-bold me-2">Contact :</span> {{ $student->user->phone ?? 'N/A' }}<br>
                        <span class="fw-bold me-2">Email :</span> {{ $student->user->email ?? 'N/A' }}
                    </address>
                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-lg-4">
            <div class="card" style="height: 13rem;">
                <div class="card-body">
                    <h4 class="header-title mb-3">Admission Information</h4>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <p class="mb-2"><span class="fw-bold me-2">Admission Number :</span> {{ $student->admission_number  ?? 'N/A' }}</p>
                            <p class="mb-2"><span class="fw-bold me-2">Admission Date:</span> {{ $student->admission_date ?? 'N/A' }}</p>
                            <p class="mb-2"><span class="fw-bold me-2">Guardian Name:</span> {{ $student->guardian_name ?? 'N/A' }}</p>
                            <p class="mb-0"><span class="fw-bold me-2">Guardian Contact:</span> {{ $student->guardian_contact ?? 'N/A' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-lg-4">
            <div class="card" style="height: 13rem;">
                <div class="card-body">
                    <h4 class="header-title mb-3">Feedback Information</h4>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <p class="mb-2"><span class="fw-bold me-2">Feedback Count :</span>{{ $student_feedbacks->count() }}</p>
                            <p class="mb-2"><span class="fw-bold me-2">Feedback History Count:</span>  {{ $feedback_histories->count() }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    <!-- Student Feedbacks  -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                            <h4 class="header-title mb-3">Student Feedbacks</h4>
                        </div>
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        @php
                            $hasMeetingLink = $student_feedbacks->contains(fn($feedback) => !empty($feedback->session?->meeting_link));
                        @endphp

                        {{-- <table class="table mb-0"> --}}
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="feedback-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Module</th>
                                    <th>Created At</th>
                                    @if ($hasMeetingLink)
                                        <th>Scheduled At</th>
                                    @endif
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student_feedbacks as $feedback)
                                    <tr>
                                        <td style="display:none !important;">{{ $feedback->id }}</td>

                                        <td>{{ $feedback->subject->name ?? 'N/A' }}</td>
                                        
                                        <td>{{ $feedback->created_at ? $feedback->created_at->format('d M Y') : '-' }}</td>

                                        
                                        @if ($hasMeetingLink)
                                            <td>
                                                @if (!empty($feedback->session?->meeting_link))
                                                    @if (!empty($feedback->session?->scheduled_at))
                                                        {{ $feedback->session->scheduled_at->format('d M Y h:i A') }}
                                                    @else
                                                        <span class="small text-danger">Not Scheduled</span>
                                                    @endif
                                                @else
                                                    <span class="small text-danger">Not Scheduled</span>
                                                @endif
                                            </td>
                                        @endif

                                        @php
                                            $statusBadges = [
                                                'pending'   => ['label' => 'Pending',   'class' => 'bg-warning'],
                                                'initiated' => ['label' => 'Initiated', 'class' => 'bg-primary'],
                                                'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-info'],
                                                'draft'     => ['label' => 'Draft',     'class' => 'bg-secondary'],
                                                'completed' => ['label' => 'Completed', 'class' => 'bg-success'],
                                                'approved'  => ['label' => 'Approved',  'class' => 'bg-success'],
                                                'rejected'  => ['label' => 'Rejected',  'class' => 'bg-danger'],
                                            ];
                                            $status = $feedback->status;
                                            $badge = $statusBadges[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-dark'];
                                        @endphp

                                        <td>
                                            <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                        </td>

                                        <td>
                                            @if (!empty($feedback->session?->meeting_link))

                                                {{-- Join Session --}}
                                                <a href="{{ $feedback->session->meeting_link }}" target="_blank" class="btn btn-sm btn-success ms-2">
                                                    <i class="uil-users-alt"></i>
                                                    Join
                                                </a>

                                                {{-- Edit Session --}}
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger ms-2 open-session-modal"
                                                    data-mode="edit"
                                                    data-session-id="{{ $feedback->session->id }}"
                                                    data-feedback-id="{{ $feedback->id }}"
                                                    data-module-id="{{ $feedback->module_id }}"
                                                    data-module-name="{{ $feedback->subject->name ?? 'N/A' }}"
                                                    data-scheduled-at="{{ $feedback->session->scheduled_at }}"
                                                    data-meeting-link="{{ $feedback->session->meeting_link }}">
                                                    <i class="mdi mdi-pencil me-1"></i>
                                                    Edit
                                                </a>

                                                {{-- View Session --}}
                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary ms-2 open-session-modal"
                                                    data-mode="view"
                                                    data-session-id="{{ $feedback->session->id }}"
                                                    data-module-id="{{ $feedback->module_id }}"
                                                    data-module-name="{{ $feedback->subject->name ?? 'N/A' }}"
                                                    data-scheduled-at="{{ $feedback->session->scheduled_at }}"
                                                    data-meeting-link="{{ $feedback->session->meeting_link }}">
                                                    <i class="mdi mdi-eye me-1"></i>
                                                    View
                                                </a>

                                                {{-- Review Session --}}
                                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary ms-2 open-review-modal"
                                                    data-mode="edit"
                                                    data-feedback-id="{{ $feedback->id }}"
                                                    data-module-id="{{ $feedback->module_id }}">
                                                    <i class="uil-comments-alt"></i>
                                                    Review
                                                </a>

                                            @else
                                                {{-- Schedule Session --}}
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger ms-2 open-session-modal"
                                                    data-mode="add"
                                                    data-module-id="{{ $feedback->module_id }}"
                                                    data-module-name="{{ $feedback->subject->name ?? 'N/A' }}"
                                                    data-feedback-id="{{ $feedback->id }}">
                                                    <i class=" uil-clock-nine"></i>
                                                    Schedule
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- end table-responsive -->
                </div>
            </div>
        </div>
    </div>

    <!-- Student Feedback Histories -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                            <h4 class="header-title mb-3">Student Feedback Histories</h4>
                        </div>
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="feedback-history-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Module</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($feedback_histories as $history)
                                    <tr>
                                        <td style="display:none !important;">{{ $history->id }}</td>
                                        
                                        <td>{{ $history->subject->name ?? 'N/A' }}</td>
                                        
                                        <td><div>{{ $history->created_at->format('d M Y') }}</div></td>

                                        @php
                                            $statusBadges = [
                                                'draft'     => ['label' => 'Draft',     'class' => 'bg-secondary'],
                                                'approved'  => ['label' => 'Approved',  'class' => 'bg-success'],
                                                'rejected'  => ['label' => 'Rejected',  'class' => 'bg-danger'],
                                            ];
                                            $status = $history->status;
                                            $badge = $statusBadges[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-dark'];
                                        @endphp

                                        <td>
                                            <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                        </td>
                                        <!-- Action Buttons -->
                                        <td>
                                            @php
                                                $isEditable = in_array($status, ['draft', 'rejected']);
                                            @endphp

                                            <a href="javascript:void(0);"
                                            class="btn btn-sm open-feedback-history-modal {{ $isEditable ? 'btn-danger' : 'btn-primary' }}"
                                            data-mode="{{ $isEditable ? 'edit' : 'view' }}"
                                            data-feedback-history-id="{{ $history->id }}">
                                                <i class="mdi {{ $isEditable ? 'mdi-pencil' : 'mdi-eye' }} me-1"></i>
                                                {{ $isEditable ? 'Edit' : 'View' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <!-- POPUP MODAL FOR EDIT-JOIN-VIEW-->
    <div class="modal fade" id="feedbackSessionModal" tabindex="-1" role="dialog" aria-labelledby="feedbackSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="feedbackSessionForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="feedbackSessionModalLabel">Session</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="feedback_id" id="feedback_id">
                        <input type="hidden" name="session_id" id="session_id">
                        <input type="hidden" name="module_id" id="module_id">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ $student->first_name }} {{ $student->last_name }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Module</label>
                                    <input type="text" class="form-control" id="module" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Scheduled At</label>
                                    <input type="datetime-local" name="scheduled_at" class="form-control" id="scheduled_at" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Admission Number</label>
                                    <input type="text" class="form-control" id="admission_number" value="{{ $student->admission_number ?? 'N/A' }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meeting Link</label>
                                    <input type="url" name="meeting_link" class="form-control" id="meeting_link" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-start" id="modal-footer-buttons">
                            <button type="button" class="btn btn-danger" id="customResetBtn">Reset</button>
                            <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- REVIEW MODAL-->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="reviewForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">Review Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="student_feedback_id" id="review_student_feedback_id">
                        <input type="hidden" name="module_id" id="review_module_id">

                        <div class="mb-3">
                            <label for="student_summary" class="form-label">Student Summary</label>
                            <textarea name="student_summary" id="student_summary" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="qc_feedback_summary" class="form-label">QC Feedback Summary</label>
                            <textarea name="qc_feedback_summary" id="qc_feedback_summary" class="form-control" rows="3" required></textarea>
                        </div>
                       
                        <div class="row mb-3">
                            <div class="col">
                                <label for="video_rating" class="form-label">Video Rating</label>
                                <input type="number" min="1" max="5" name="video_rating" id="video_rating" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="practical_rating" class="form-label">Practical Rating</label>
                                <input type="number" min="1" max="5" name="practical_rating" id="practical_rating" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="understanding_rating" class="form-label">Understanding Rating</label>
                                <input type="number" min="1" max="5" name="understanding_rating" id="understanding_rating" class="form-control" required>
                            </div>

                             <div class="col">
                                <label class="form-label">Status</label>
                                <select name="history_status" id="history_status" class="form-control">
                                    <option value="">Select option</option>
                                    @foreach($history_status as $key => $label)
                                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="small text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Draft Review</button>
                        <button type="submit" class="btn btn-primary">Save Review</button>
                    </div>
                </div>
            </form>
        </div>
    </div>   
    	
    <!-- Feedback History Modal -->
    <div class="modal fade" id="feedbackHistoryModal" tabindex="-1" aria-labelledby="feedbackHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Feedback History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedbackHistoryForm">
                        <input type="hidden" name="id">

                        <div class="mb-3">
                            <label for="student_summary" class="form-label">Student Summary</label>
                            <textarea name="student_summary" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="qc_feedback_summary" class="form-label">QC Feedback Summary</label>
                            <textarea name="qc_feedback_summary" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Video Rating</label>
                                <input type="number" name="video_rating" class="form-control" min="1" max="5">
                            </div>
                            <div class="col">
                                <label class="form-label">Practical Rating</label>
                                <input type="number" name="practical_rating" class="form-control" min="1" max="5">
                            </div>
                            <div class="col">
                                <label class="form-label">Understanding Rating</label>
                                <input type="number" name="understanding_rating" class="form-control" min="1" max="5">
                            </div>
                            <div class="col">
                                <label class="form-label">Status</label>
                                <select name="history_status" class="form-control">
                                    <option value="">Select option</option>
                                    @foreach($feedback_history_status as $key => $label)
                                        <option value="{{ strtolower($key) }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="saveFeedbackBtn" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('feedback-scripts')

        <!-- Student Feedback for edit-join-view-->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('feedbackSessionModal'));
                const form = document.getElementById('feedbackSessionForm');
                const scheduledInput = document.getElementById('scheduled_at');
                const linkInput = document.getElementById('meeting_link');
                const moduleInput = document.getElementById('module');
                const moduleIdInput = document.getElementById('module_id');
                const submitBtn = document.getElementById('submitButton');
                const footerButtons = document.getElementById('modal-footer-buttons');
                const modalTitle = document.getElementById('feedbackSessionModalLabel');

                let originalValues = {
                    scheduled_at: '',
                    meeting_link: ''
                };

                document.addEventListener('click', function (e) {
                    const button = e.target.closest('.open-session-modal');
                    if (!button) return;
                    const mode = button.dataset.mode;

                    document.getElementById('feedback_id').value = button.dataset.feedbackId || '';
                    document.getElementById('session_id').value = button.dataset.sessionId || '';
                    moduleIdInput.value = button.dataset.moduleId || '';
                    moduleInput.value = button.dataset.moduleName || '';

                    const scheduledAt = button.dataset.scheduledAt?.replace(' ', 'T') || '';
                    const meetingLink = button.dataset.meetingLink || '';

                    scheduledInput.value = scheduledAt;
                    linkInput.value = meetingLink;

                    originalValues.scheduled_at = scheduledAt;
                    originalValues.meeting_link = meetingLink;

                    scheduledInput.removeAttribute('disabled');
                    linkInput.removeAttribute('disabled');

                    if (mode === 'add') {
                        modalTitle.textContent = 'Schedule Meeting';
                        form.action = "{{ route('lms.store.feedback.session') }}";
                        form.method = 'POST';
                        form.querySelector('input[name="_method"]')?.remove();
                        submitBtn.textContent = 'Create';
                        submitBtn.style.display = 'inline-block';
                        footerButtons.style.display = 'block';

                    } else if (mode === 'edit') {
                        modalTitle.textContent = 'Edit Meeting';
                        form.action = "{{ route('lms.update.feedback.session', ['id' => '__ID__']) }}".replace('__ID__', button.dataset.sessionId);
                        form.method = 'POST';
                        form.querySelector('input[name="_method"]')?.remove();
                        submitBtn.textContent = 'Update';
                        submitBtn.style.display = 'inline-block';
                        footerButtons.style.display = 'block';

                    } else if (mode === 'view') {
                        modalTitle.textContent = 'View Meeting';
                        scheduledInput.setAttribute('disabled', 'disabled');
                        linkInput.setAttribute('disabled', 'disabled');
                        submitBtn.style.display = 'none';
                        footerButtons.style.display = 'none';
                    }
                    modal.show();
                });

                document.getElementById('customResetBtn')?.addEventListener('click', function () {
                    scheduledInput.value = originalValues.scheduled_at;
                    linkInput.value = originalValues.meeting_link;
                });
            });
        </script>

        <!-- Student Feedback history for store -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const reviewModalEl = document.getElementById('reviewModal');
                const reviewModal = new bootstrap.Modal(reviewModalEl);
                const reviewForm = document.getElementById('reviewForm');
                const feedbackStoreUrl = "{{ route('lms.store.feedback.history') }}";

                document.addEventListener('click', function (e) {
                    const button = e.target.closest('.open-review-modal');
                    if (!button) return;

                    document.getElementById('review_student_feedback_id').value = button.dataset.feedbackId || '';
                    document.getElementById('review_module_id').value = button.dataset.moduleId || '';

                    reviewForm.reset();
                    reviewModal.show();
                });

                reviewForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(reviewForm);

                    fetch(feedbackStoreUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Server error response:', errorData);
                            if (response.status === 422) {
                                const errors = errorData.errors || {};
                                const messages = Object.values(errors).flat().join('\n');
                                alert('Validation Error:\n' + messages);
                            } else {
                                alert('Server error occurred.');
                            }
                            throw new Error('Request failed');
                        }
                        return response.json();
                    })
                    .then(data => {
                        reviewModal.hide();
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Error saving review.');
                    });
                });
            });
        </script>

        <!-- feedback-datatable -->
        <script>
            $(document).ready(function () {
                "use strict";
                if (!$.fn.DataTable) {
                    console.error("DataTables library is missing or not loaded.");
                    return;
                }
                $("#feedback-datatable").DataTable({
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'></i>",
                            next: "<i class='mdi mdi-chevron-right'></i>"
                        },
                        info: "Showing rows _START_ to _END_ of _TOTAL_",
                        lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="-1">All</option>' +
                            '</select> rows'
                    },
                    pageLength: 10,
                    autoWidth: false,
                    responsive: true, 
                    order: [[0, 'desc']],
                    columnDefs: [
                        {
                            targets: 0,         
                            visible: false,     
                            searchable: false  
                        }
                    ],
                    drawCallback: function () {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        $("#feedback-datatable_length label").addClass("form-label");
                        document.querySelectorAll(".dataTables_wrapper .row .col-md-6").forEach(function (el) {
                            el.classList.add("col-sm-6");
                            el.classList.remove("col-sm-12", "col-md-6");
                        });
                    }
                });
            });

        </script>

        <!-- feedback-history-datatable -->
        <script>
            $(document).ready(function () {
                "use strict";
                if (!$.fn.DataTable) {
                    console.error("DataTables library is missing or not loaded.");
                    return;
                }
                $("#feedback-history-datatable").DataTable({
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'></i>",
                            next: "<i class='mdi mdi-chevron-right'></i>"
                        },
                        info: "Showing rows _START_ to _END_ of _TOTAL_",
                        lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="-1">All</option>' +
                            '</select> rows'
                    },
                    pageLength: 10,
                    autoWidth: false, 
                    responsive: true, 
                    order: [[0, 'desc']],
                    columnDefs: [
                        {
                            targets: 0,         
                            visible: false,     
                            searchable: false   
                        }
                    ],
                    drawCallback: function () {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        $("#feedback-history-datatable_length label").addClass("form-label");
                        document.querySelectorAll(".dataTables_wrapper .row .col-md-6").forEach(function (el) {
                            el.classList.add("col-sm-6");
                            el.classList.remove("col-sm-12", "col-md-6");
                        });
                    }
                });
            });

        </script>

        <!-- feedback-history for edit-view-->
        <script>
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });

                $(document).on('click', '.open-feedback-history-modal', function () {
                    const id = $(this).data('feedback-history-id');
                    const mode = $(this).data('mode');
                    const url = `{{ route('lms.edit.feedback.history', ['id' => '__id__']) }}`.replace('__id__', id);
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            const $modal = $('#feedbackHistoryModal');
                            const $form = $modal.find('form');
                            // Populate fields using scoped selectors
                            $form.find('[name="id"]').val(data.id || '');
                            $form.find('[name="student_summary"]').val(data.student_summary || '');
                            $form.find('[name="qc_feedback_summary"]').val(data.qc_feedback_summary || '');
                            $form.find('[name="video_rating"]').val(data.video_rating || '');
                            $form.find('[name="practical_rating"]').val(data.practical_rating || '');
                            $form.find('[name="understanding_rating"]').val(data.understanding_rating || '');

                            const statusValue = (data.status || '').toLowerCase();
                            $form.find('[name="history_status"]').val(statusValue);
                            if ($form.find('[name="history_status"]').val() !== statusValue) {
                                $form.find('[name="history_status"] option').each(function () {
                                    if ($(this).val().toLowerCase() === statusValue) {
                                        $(this).prop('selected', true);
                                    }
                                });
                            }

                            // Enable/disable fields based on mode
                            if (mode === 'view') {
                                $form.find('input, textarea, select').prop('disabled', true);
                                $('#saveFeedbackBtn').hide();
                            } else {
                                $form.find('input, textarea, select').prop('disabled', false);
                                $('#saveFeedbackBtn').show();
                            }
                            // Show modal
                            const modal = new bootstrap.Modal(document.getElementById('feedbackHistoryModal'));
                            modal.show();
                        },
                        error: function (xhr) {
                            alert('Failed to load feedback history.');
                            console.error(xhr.responseText);
                        }
                    });
                });

                // Submit form
                $('#feedbackHistoryForm').on('submit', function (e) {
                    e.preventDefault();
                    const id = $(this).find('[name="id"]').val();
                    const url = `{{ route('lms.update.feedback.history', ['id' => '__id__']) }}`.replace('__id__', id);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function () {
                            // alert('Feedback updated successfully.');
                            const modalEl = document.getElementById('feedbackHistoryModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                            // Small delay before reloading to let modal hide smoothly
                            setTimeout(() => {
                                location.reload();
                            }, 300);
                        },
                        error: function (xhr) {
                            alert('Error updating feedback.');
                            console.error(xhr.responseText);
                        }
                    });
                });

                // Reset form when modal closes
                $('#feedbackHistoryModal').on('hidden.bs.modal', function () {
                    const $form = $(this).find('form')[0];
                    $form.reset();
                    $(this).find('input, textarea, select').prop('disabled', false);
                    $('#saveFeedbackBtn').show();
                });
            });
        </script>
    @endpush

@endsection