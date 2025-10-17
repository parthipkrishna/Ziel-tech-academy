<!-- Delete Alert Modal -->
<div id="delete-alert-modal{{ $course->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="ri-information-line h1 text-info"></i>
                <h4 class="mt-3">Heads up!</h4>
                <p class="mt-2">Are you sure you want to delete this<br><strong>Course Section</strong>?</p>

                <button class="btn btn-danger my-2 delete-course-btn" data-id="{{ $course->id }}">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
