@extends('lms.layout.layout')
@section('list-faq')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Marketing Management</a></li>
                        <li class="breadcrumb-item active">FAQ</li>
                    </ol>
                </div>
                <h4 class="page-title">FAQs</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('faqs.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.faq') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="faqs-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Is Enable</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>

                        @foreach ($faqs as $faq)
                        <!-- Edit Modal-->
                        <div class="modal fade" id="edit-faq-modal{{ $faq->id }}" tabindex="-1" role="dialog" aria-labelledby="editFaqLabel{{ $faq->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="editFaqLabel{{ $faq->id }}">Edit FAQ</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lms.update.faq', $faq->id) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <!-- Left Column -->
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="question{{ $faq->id }}" class="form-label">Question</label>
                                                        <textarea name="question" class="form-control" id="question{{ $faq->id }}" required>{{ $faq->question }}</textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="answer{{ $faq->id }}" class="form-label">Answer</label>
                                                        <textarea name="answer" class="form-control" id="answer{{ $faq->id }}" required>{{ $faq->answer }}</textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="is_enable{{ $faq->id }}" class="form-label">is_enable</label><br>
                                                        <input type="hidden" name="is_enable" value="0">
                                                        <input type="checkbox" name="is_enable" id="switch-is_enable{{ $faq->id }}" value="1" {{ $faq->is_enable ? 'checked' : '' }} data-switch="success" />
                                                        <label for="switch-is_enable{{ $faq->id }}" data-on-label="" data-off-label=""></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        <div id="delete-faq-modal{{ $faq->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="ri-information-line h1 text-info"></i>
                                            <h4 class="mt-2">Heads up!</h4>
                                            <p class="mt-3">Do you want to delete this FAQ?</p>
                                            <button type="button" class="btn btn-danger my-2 confirm-delete-faq" data-id="{{ $faq->id }}">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach       
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
     <script>

        function bindDeleteFaqEvent() {
            $('.confirm-delete-faq').off('click').on('click', function () {
                let $btn = $(this);
                let faqId = $btn.data('id');
                let url = '{{ route("lms.delete.faq", ":id") }}'.replace(':id', faqId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#delete-faq-modal' + faqId).modal('hide');
                        $('#faqs-datatable').DataTable().ajax.reload(null, false);  
                    },
                    error: function () {
                        alert('Something went wrong. Could not delete FAQ.');
                    }
                });
            });
        }

    $(document).ready(function () {
        $('#faqs-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('faqs.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'question' },
                { data: 'answer' },
                { data: 'is_enable', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']],
            responsive: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteFaqEvent();
                
                // Initialize status toggle switches
            }
        });
    });
    </script>
@endsection
