@extends('layouts.dashboard')
@section('list-campus')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Campus</a></li>
                        <li class="breadcrumb-item active">Campus Facilities</li>
                    </ol>
                </div>
                <h4 class="page-title">Campus Facilities</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('campus-facilities', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.campusfacilities.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                    <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campusFacilities as $facility)
                                <tr>
                                    <td>{{ $facility->description }}</td>
                                    <td>
                                        <img src="{{ env('STORAGE_URL') . '/' . $facility->image }}" alt="Facility Image" style="max-width: 100px;">
                                    </td>
                                    <td>
                                        @if(auth()->user()->hasPermission('campus-facilities', 'edit'))
                                            <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editFacility-modal{{ $facility->id }}">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('campus-facilities', 'delete'))
                                            <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $facility->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Edit Modal-->
                                <div class="modal fade" id="bs-editFacility-modal{{ $facility->id }}" tabindex="-1" role="dialog" aria-labelledby="editFacilityLabel{{ $facility->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="editFacilityLabel{{ $facility->id }}">Edit Facility</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.campusfacilities.update',$facility->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="description">Description</label>
                                                                <textarea class="form-control" name="description" id="description" rows="4" required>{{ $facility->description }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="image">Image</label>
                                                                <input type="file" name="image" class="form-control" id="image">
                                                                @if($facility->image)
                                                                    <img src="{{ env('STORAGE_URL') . '/' . $facility->image}}" alt="Facility Image" style="max-width: 100px; margin-top: 10px;">
                                                                @endif
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="status" class="form-label">Status: </label></br/>
                                                                <input type="hidden" name="status" value="0">
                                                                <input type="checkbox" name="status" id="switch{{ $facility->id }}" value="1"  {{ $facility->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                                                <label for="switch{{ $facility->id }}" data-on-label="" data-off-label=""></label>
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
                                <div id="delete-alert-modal{{ $facility->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-body p-4">
                                                <div class="text-center">
                                                    <i class="ri-information-line h1 text-info"></i>
                                                    <h4 class="mt-2">Heads up!</h4>
                                                    <p class="mt-3">Do you want to delete this Facility?</p>
                                                    <form action="{{ route('admin.campusfacilities.delete',$facility->id) }}" method="POST">
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
