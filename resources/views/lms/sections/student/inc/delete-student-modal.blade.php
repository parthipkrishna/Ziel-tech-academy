<!-- Delete Alert Modal -->
<div id="delete-alert-modal{{ $student->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="ri-information-line h1 text-info"></i>
                    <h4 class="mt-2">Heads up!</h4>
                    <p class="mt-3">Do you want to delete this Student?</p>

                    <button type="button" class="btn btn-danger my-2 confirm-delete-student"
                        data-id="{{ $student->id }}">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
