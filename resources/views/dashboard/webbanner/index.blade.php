@extends('layouts.dashboard')
@section('list-web-banner')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Web Banner</a></li>
                        <li class="breadcrumb-item active">Web Banner</li>
                    </ol>
                </div>
                <h4 class="page-title">Web Banner</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('web-banners', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.web.banner.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Web Banner</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Short Description</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banners as $banner)
                                    <tr>
                                        <td class="table-user">
                                            @if ($banner->image_url)
                                                <img src="{{ env('STORAGE_URL') . '/' . $banner->image_url }}" class="me-2 rounded-circle">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($banner->title)
                                                {{ Str::limit($banner->title, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No title</span>
                                            @endif 
                                        </td>

                                        <td>{{ $banner->type }}</td>
                                        <td>
                                            @if ($banner->short_desc)
                                                {{ Str::limit($banner->short_desc, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No short description</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if ($banner->description)
                                                {{ Str::limit($banner->description, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No description</span>
                                            @endif 
                                        </td>
                                        
                                        <td>
                                        @if(auth()->user()->hasPermission('web-banners', 'edit'))
                                            <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editWebBanner-modal{{ $banner->id }}">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('web-banners', 'delete'))
                                            <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $banner->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editWebBanner-modal{{ $banner->id }}" tabindex="-1" role="dialog" aria-labelledby="editWebBannerLabel{{ $banner->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editWebBannerLabel{{ $banner->id }}">Edit Web Banner</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.web.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Title</label>
                                                                    <input type="text" name="title" value="{{ $banner->title}}" class="form-control" id="validationCustom01" placeholder="title" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="type">Select Type:</label>
                                                                    <select name="type" id="type" class="form-select" >
                                                                        <option value="">Choose a type</option>
                                                                        @foreach(\App\Models\WebBanner::getTypes() as $type)
                                                                            <option value="{{ $type }}" {{ old('type', isset($banner) ? $banner->type : '') == $type ? 'selected' : '' }}>
                                                                                {{ ucfirst($type) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Upload Image</label>
                                                                    <input type="file" name="image_url" class="form-control" >
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Image</label><br>
                                                                    @if ($banner->image_url)
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $banner->image_url }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Image</span>
                                                                    @endif
                                                                </div>

                                                            </div>
                        
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Short Description</label>
                                                                    <textarea class="form-control" name="short_desc" id="$banner->id" rows="3">{{ $banner->short_desc }}</textarea>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Description</label>
                                                                    <textarea class="form-control" name="description" id="description" rows="3">{{ $banner->description }}</textarea>
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
                                    <div id="delete-alert-modal{{ $banner->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this WebBanner?</p>
                                                        <form action="{{ route('admin.web.banner.delete', $banner->id) }}" method="POST">
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
