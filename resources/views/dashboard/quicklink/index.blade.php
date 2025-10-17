@extends('layouts.dashboard')
@section('list-quicklinks')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Quicklinks</a></li>
                        <li class="breadcrumb-item active">Quicklinks</li>
                    </ol>
                </div>
                <h4 class="page-title">Quicklinks</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('quick-links', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.quicklink.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Attachment</th>
                                    <th>Order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quicklinks as $info)
                                    <tr>
                                        <td>{{ $info->title }}</td>
                                        <td> {{ Str::limit($info->url, 25, '...') }} </td>
                                        <td>
                                            @if($info->attachment)
                                                <a href="{{ asset('storage/' . $info->attachment) }}" target="_blank">View Attachment</a>
                                            @else
                                                No Attachment
                                            @endif
                                        </td>
                                        <td>{{ $info->order }}</td>
                                        <td>
                                            @if(auth()->user()->hasPermission('quick-links', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editQuicklink-modal{{ $info->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('quick-links', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $info->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editQuicklink-modal{{ $info->id }}" tabindex="-1" role="dialog" aria-labelledby="editQuicklinkLabel{{ $info->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editQuicklinkLabel{{ $info->id }}">Edit Quicklink</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.quicklink.update', $info->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Title</label>
                                                                    <input type="text" name="title" value="{{ $info->title }}" class="form-control" id="title" placeholder="title" required>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Order</label>
                                                                    <input type="text" name="order" value="{{ $info->order }}" class="form-control" id="validationCustom01" placeholder="order" >
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">URL</label>
                                                                    <input type="text" name="url" value="{{ $info->url }}" class="form-control" id="validationCustom01" placeholder="URL">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Current Attachment</label>
                                                                    <div>
                                                                        @if($info->attachment)
                                                                            <a href="{{ asset('storage/' . $info->attachment) }}" target="_blank">View Attachment</a>
                                                                        @else
                                                                            <p>No attachment</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Type</label>
                                                                <select name="type" class="form-control" required>
                                                                    <option value="ATTACHMENT" {{ $info->type == 'ATTACHMENT' ? 'selected' : '' }}>Document</option>
                                                                    <option value="LINK" {{ $info->type == 'LINK' ? 'selected' : '' }}>Link</option>
                                                                </select>
                                                            </div>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <!-- Delete Alert Modal  -->
                                    <div id="delete-alert-modal{{ $info->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Quicklinks?</p>
                                                        <form action="{{ route('admin.quicklink.delete', $info->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger my-2">Delete</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
