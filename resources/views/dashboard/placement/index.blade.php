@extends('layouts.dashboard')
@section('list-placement')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Placements</a></li>
                        <li class="breadcrumb-item active">Placements</li>
                    </ol>
                </div>
                <h4 class="page-title">Placements</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('placements', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.placement.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Company Name</th>
                                    <th>Description</th>
                                    <th>Oppertunities</th>
                                    <th>Webite</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($placements as $company)
                                    <tr>
                                        <td>{{ $company->company_name }}</td>
                                        <td>
                                            @if ($company->description)
                                                {{ Str::limit($company->description, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No description</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if ($company->opportunities)
                                                {{ Str::limit($company->opportunities, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No opportunities</span>
                                            @endif 
                                        </td>

                                        <td>
                                            @if ($company->website)
                                                {{ Str::limit($company->website, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No website</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('placements', 'view'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editPlacement-modal{{ $company->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('placements', 'view'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $company->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editPlacement-modal{{ $company->id }}" tabindex="-1" role="dialog" aria-labelledby="editPlacementLabel{{ $company->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editPlacementLabel{{ $company->id }}">Edit Placement</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.placement.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Company Name</label>
                                                                    <input type="text" name="company_name" value="{{ $company->company_name }}" class="form-control" id="validationCustom01" placeholder="Company Name" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Opportunities</label>
                                                                    <textarea class="form-control" name="opportunities" id="opportunities" rows="3">{{ $company->opportunities }}</textarea>
                                                                </div>
                        
                                                            </div>
                        
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Website</label>
                                                                    <input type="text" name="website" value="{{ $company->website }}" class="form-control" id="validationCustom01" placeholder="Website" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Description</label>
                                                                    <textarea class="form-control" name="description" id="description" rows="3">{{ $company->description }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                    <label for="image" class="form-label">Upload Image</label>
                                                                    <input type="file" name="image" class="form-control" >
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Image</label><br>
                                                                    @if ($company->image)
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $company->image }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Image</span>
                                                                    @endif
                                                                </div>
                                                        </div>
                        
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <!-- Delete Alert Modal  -->
                                    <div id="delete-alert-modal{{ $company->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Placement?</p>
                                                        <form action="{{ route('admin.placement.delete', $company->id) }}" method="POST">
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
