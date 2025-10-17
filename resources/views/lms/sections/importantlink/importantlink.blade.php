@extends('lms.layout.layout')
@section('list-important_links')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mobile & App Content Management</a></li>
                        <li class="breadcrumb-item active">Important Link</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('important-links.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.important.link') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="important-links-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Name</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <script>
         $(document).on('change', '.status-toggle', function () {
        let linkId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.link.status", ":id") }}'.replace(':id', linkId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                console.log(response.message);
                $('#status_' + linkId).prop('checked', !!status);
            }
        });
    });

    function bindDeleteImportantLinkEvent() {
        $('.confirm-delete-link').off('click').on('click', function () {
            let linkId = $(this).data('id');
            let url = '{{ route("lms.delete.important.link", ":id") }}'.replace(':id', linkId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function (response) {
                    $('#delete-alert-modal' + linkId).modal('hide');
                    $('#important-links-datatable').DataTable().ajax.reload(null, false);
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON.message || 'Something went wrong.');
                }
            });
        });
    }

    </script>
    <script>
    $(document).ready(function () {
        $('#important-links-datatable').DataTable({
            serverSide: true,
            responsive: true,
            ajax: '{{ route('important-links.ajax') }}',
            pageLength: 25,
            columns: [
                { data: 'id', visible: false },
                { data: 'name', name: 'name' },
                { data: 'link', name: 'link' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                },
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                    '<option value="5">5</option>' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="-1">All</option>' +
                    '</select> entries'
            },
            pageLength: 10,
            order: [[0, "desc"]],
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteImportantLinkEvent();
            },
        });
    });
    </script>

@endsection
