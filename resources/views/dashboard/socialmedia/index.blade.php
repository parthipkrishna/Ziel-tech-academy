@extends('layouts.dashboard')
@section('list-socialmedia')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Social Media</a></li>
                        <li class="breadcrumb-item active">Social Media</li>
                    </ol>
                </div>
                <h4 class="page-title">Social Media</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('social-media-link', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.socialmedia.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Platform</th>
                                    <th>URL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($links as $link)
                                    <tr>
                                        <td>{{ $link->platform }}</td>
                                        <td> {{ Str::limit($link->url, 25, '...') }} </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('social-media-link', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editSocialMedia-modal{{ $link->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('social-media-link', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $link->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editSocialMedia-modal{{ $link->id }}" tabindex="-1" role="dialog" aria-labelledby="editSocialMediaLabel{{ $link->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editSocialMediaLabel{{ $link->id }}">Edit Social Media</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.socialmedia.update', $link->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">                        
                                                                <div class="mb-3">
                                                                    <label for="platform" class="form-label">Platform</label>
                                                                    <select name="platform" class="form-select" >
                                                                        <option value="">{{  $link->platform }}</option>
                                                                        <option value="">Choose a platform</option>
                                                                        @foreach (\App\Models\SocialMediaLink::getPlatformOptions() as $option)
                                                                            <option value="{{ $option }}" {{ old('platform') == $option ? 'selected' : '' }}>
                                                                                {{ ucfirst($option) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom02">URL</label>
                                                                    <input type="text" name="url" value="{{ $link->url }}" class="form-control" id="validationCustom02" placeholder="URL" >
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
                                    <div id="delete-alert-modal{{ $link->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-linkrmation-line h1 text-link"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Social Media?</p>
                                                        <form action="{{ route('admin.socialmedia.delete', $link->id) }}" method="POST">
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
