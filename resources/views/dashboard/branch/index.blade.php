@extends('layouts.dashboard')
@section('list-branches')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Branches</a></li>
                        <li class="breadcrumb-item active"> Branches</li>
                    </ol>
                </div>
                <h4 class="page-title"> Branches</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('branches', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.branches.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th >Branch</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>GoogleMap Link</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branch_main as $branch)
                                    <tr>
                                        <td class="table-user">
                                            @if ($branch['image'])
                                                <img src="{{ env('STORAGE_URL') . '/' . $branch['image']  }}" class="me-2 rounded-circle">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $branch['name'] }}</td>
                                        <td>{{ Str::limit($branch['address'], 25, '...') }}</td>
                                        <td>
                                            @if ($branch['contact_number'])
                                                {{ $branch['contact_number'] }}
                                            @else
                                                <span class="small text-danger">No Contact Number</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if ($branch['google_map_link'])
                                                {{ Str::limit($branch['google_map_link'], 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No Google Map Link</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('branches', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editBranch-modal{{ $branch['id'] }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('branches', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $branch['id'] }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editBranch-modal{{ $branch['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editBranchLabel{{ $branch['id'] }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editBranchLabel{{ $branch['id'] }}">Edit Shope User</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.branches.update', $branch['id']) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf   
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Name</label>
                                                                    <input type="text" name="name" value="{{ $branch['name'] }}" class="form-control" id="name" placeholder="Name">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Address</label>
                                                                    <textarea class="form-control" name="address" id="address" rows="3">{{ $branch['address'] }}</textarea>
                                                                </div>                      
                                                                <div class="mb-3">
                                                                    <label for="status_{{ $branch['id'] }}" class="form-label">Status: </label></br/>
                                                                    <input type="hidden" name="status" value="0">
                                                                    <input type="checkbox" name="status" id="status_{{ $branch['id'] }}" value="1"  {{ $branch['status'] == 1 ? 'checked' : '' }}  data-switch="success" />
                                                                    <label for="status_{{ $branch['id'] }}" data-on-label="" data-off-label=""></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6"> 
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Phone Number</label>
                                                                    <input type="text" name="contact_number" value="{{ $branch['contact_number'] }}" class="form-control" id="validationCustom01" placeholder="Phone Number" >
                                                                </div>
                       
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom02">Google Map</label>
                                                                    <input type="text" name="google_map_link" value="{{ $branch['google_map_link'] }}" class="form-control" id="validationCustom02" placeholder="Google Map">
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Upload Logo</label>
                                                                    <input type="file" name="image" class="form-control" >
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Image</label><br>
                                                                    @if ($branch['image'])
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $branch['image'] }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Image</span>
                                                                    @endif
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
                                    <div id="delete-alert-modal{{ $branch['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Branch?</p>
                                                        <form action="{{ route('admin.branches.delete', $branch['id']) }}" method="POST">
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
