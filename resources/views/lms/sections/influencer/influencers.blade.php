@extends('lms.layout.layout')
@section('list-influencer')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Referral & Affiliate System</a></li>
                        <li class="breadcrumb-item active">Influencers</li>
                    </ol>
                </div>
                <h4 class="page-title">Influencers</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('influencers.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.influencer') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="influencers-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Influencer</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Referral Code</th>
                                    <th>Commission</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

    <script>
        $(document).ready(function () {
            $('.status-toggle').change(function () {
                let courseId = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;

                // Update hidden input field before form submission
                $('#hidden_status_' + courseId).val(status);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('lms.update.student', ':id') }}".replace(':id', courseId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function (response) {
                        location.reload();
                    },
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-link').forEach(function (el) {
                el.addEventListener('click', function () {
                    const link = this.getAttribute('data-link');
                    const successEl = this.nextElementSibling;

                    navigator.clipboard.writeText(link).then(() => {
                        if (successEl && successEl.classList.contains('copy-success')) {
                            successEl.classList.remove('d-none');
                            setTimeout(() => {
                                successEl.classList.add('d-none');
                            }, 1500); // Show for 1.5 seconds
                        }
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                    });
                });
            });
        });

        function bindDeleteInfluencerEvent() {
            $('.confirm-delete-influencer').off('click').on('click', function () {
                let influencerId = $(this).data('id');
                let url = '{{ route("lms.delete.influencer", ":id") }}'.replace(':id', influencerId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#delete-alert-modal' + influencerId).modal('hide');

                        // Optional: Remove influencer row or reload datatable
                        $('#influencers-datatable').DataTable().ajax.reload(null, false);

                        toastr.success(response.message);
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.message || 'Failed to delete influencer.');
                    }
                });
            });
        }

        $(document).ready(function () {
            bindDeleteInfluencerEvent();
        });

    </script>
    <script>
        $(document).ready(function() {
        $('#influencers-datatable').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('influencers.ajax.list') }}",
                type: "GET"
            },
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'code', name: 'referralCode.code' },
                { data: 'commission', name: 'commission_per_user' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
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
                bindDeleteInfluencerEvent();
                
                // Initialize copy link functionality
                $('.copy-link').click(function() {
                    const link = $(this).data('link');
                    navigator.clipboard.writeText(link);
                    $(this).next('.copy-success').removeClass('d-none').fadeOut(2000);
                });
            }
        });
    });
    </script>

@endsection      