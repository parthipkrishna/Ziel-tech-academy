@extends('lms.layout.layout')
@section('list-banners')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Payment Gateway Config</li>
                </ol>
            </div>
            <h4 class="page-title">Payment Gateway Configurations</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body"> 
                 @if(auth()->user()->hasPermission('payment-config.create'))
                <div class="col-sm-5">
                    <a href="{{route('lms.payment-config.create')}}" class="btn btn-danger mb-2 action-icon"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="payment-configs-datatable">
                        <thead class="table-dark">
                            <tr>
                                <th>Display Name</th>
                                <th>Gateway Name</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    @foreach ($paymentConfigs as $config)
                    <!-- Edit Modal -->
                    <div class="modal fade" id="edit-config-modal{{ $config->id }}" tabindex="-1" role="dialog" aria-labelledby="editConfigLabel{{ $config->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="editConfigLabel{{ $config->id }}">Edit Payment Config</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('lms.payment-configs.update', $config->id) }}">
                                        @csrf
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gateway Name</label>
                                                    <input type="text" name="gateway_name" class="form-control" value="{{ $config->gateway_name }}" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Display Name</label>
                                                    <input type="text" name="display_name" class="form-control" value="{{ $config->display_name }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">API Key</label>
                                                    <input type="text" name="api_key" class="form-control" placeholder="Leave blank to keep current">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Meta (JSON)</label>
                                                    <textarea name="meta" class="form-control" placeholder='{"key":"value"}'>
                                                        {{ json_encode($config->meta, JSON_PRETTY_PRINT) }}
                                                    </textarea>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">API Secret</label>
                                                    <input type="text" name="api_secret" class="form-control" placeholder="Leave blank to keep current">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Webhook Secret</label>
                                                    <input type="text" name="webhook_secret" class="form-control" placeholder="Leave blank to keep current">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Status</label><br/>
                                                    <input type="checkbox" name="status" id="statusSwitchInModal{{ $config->id }}" class="edit-modal-status-toggle" value="active"
                                                        {{ $config->status === 'active' ? 'checked' : '' }}
                                                        data-switch="success"
                                                        onchange="this.value = this.checked ? 'active' : 'inactive';" />
                                                    <label for="statusSwitchInModal{{ $config->id }}" data-on-label="Active" data-off-label="Inactive"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-start">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    <!-- Delete Modal -->
                   <div id="delete-config-modal{{ $config->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body p-4">
                                <div class="text-center">
                                    <i class="ri-information-line h1 text-info"></i>
                                    <h4 class="mt-2">Heads up!</h4>
                                    <p class="mt-3">Do you want to delete this Payment Config?</p>
                                    <button type="button" class="btn btn-danger my-2 confirm-delete-config" data-id="{{ $config->id }}">Delete</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

<script>

        $(document).on('change', '.status-toggle', function() {
            let toggleElement = $(this);
            let configId = toggleElement.data('id');
            let isChecked = toggleElement.is(':checked');
            let status = isChecked ? 'active' : 'inactive';

            // Prevent deactivating the only active gateway
            if (!isChecked && $('.status-toggle:checked').length === 1) {
                toastr.warning('At least one payment gateway must be active.');
                toggleElement.prop('checked', true); // Revert the toggle back to active
                return;
            }

            $.ajax({
                url: '{{ route("payment-configs.toggleStatus", ":id") }}'.replace(':id', configId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function (response) {
                    if (isChecked) {
                        $('.status-toggle').not(toggleElement).prop('checked', false);
                        $('.edit-modal-status-toggle').not('#statusSwitchInModal' + configId).prop('checked', false);
                    }

                    $('#statusSwitchInModal' + configId).prop('checked', isChecked);
                    
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toggleElement.prop('checked', !isChecked);
                    toastr.error('Failed to update gateway status.');
                }
            });
        });

    function bindDeleteConfigEvent() {
            $('.confirm-delete-config').off('click').on('click', function () {
                let configId = $(this).data('id');
                let url = '{{ route("lms.payment-configs.delete", ":id") }}'.replace(':id', configId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#delete-config-modal' + configId).modal('hide');
                        $('#payment-configs-datatable').DataTable().ajax.reload(null, false);
                        toastr.success('Payment config deleted successfully.');
                    },
                    error: function () {
                        toastr.error('Something went wrong. Could not delete payment config.');
                    }
                });
            });
        }

    $(document).ready(function () {
        $('#payment-configs-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('payment_configs.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'display_name' },
                { data: 'gateway_name' },
                { data: 'updated_at' },
                { data: 'status', orderable: false, searchable: false },
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
                bindDeleteConfigEvent();
                
                // Initialize status toggle switches
            }
        });
    });
</script>

@endsection
