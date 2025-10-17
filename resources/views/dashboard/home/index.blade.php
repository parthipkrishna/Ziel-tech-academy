@extends('layouts.dashboard')

@section('admin-home')
@php
use Illuminate\Support\Str;
@endphp
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">SmartXKerala</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
                <h4 class="page-title">Home</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                            <a href="{{ route('quick-link.add') }}" class="btn btn-danger mb-2">
                                <i class="mdi mdi-plus-circle me-2"></i> Add
                            </a>
                        </div>
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Icon</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                    <th style="width: 75px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quicklinks as $item)
                                    <tr>
                                        </td>
                                        <td class="table-user">
                                            @if ($item->image)
                                                <img src="{{ env('APP_URL') . '/' . str_replace('public/', '', $item->image) }}" class="me-2 rounded-circle" width="40" height="40">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->subtitle }}</td>
                                        <td>{{ Str::limit($item->link, 24, '...') }}</td>

                                        <td>
                                            @if($item->status == 1)
                                                <button type="button" class="btn btn-soft-success rounded-pill">Active</button>
                                            @else
                                                <button type="button" class="btn btn-soft-danger rounded-pill">Deactive</button>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editQuicklink-modal{{ $item->quick_link_id }}">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $item->quick_link_id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editQuicklink-modal{{ $item->quick_link_id }}" tabindex="-1" role="dialog" aria-labelledby="editQuicklinkLabel{{ $item->quick_link_id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editQuicklinkLabel{{ $item->quick_link_id }}">Edit Quick Link</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('quick-link.update/{id}', $item->quick_link_id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <!-- Name Field -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="title" class="form-label">Title</label>
                                                                    <input type="text" class="form-control" id="title" name="title" value="{{ $item->title }}">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="subtitle" class="form-label">Subtitle</label>
                                                                    <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ $item->subtitle }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="link" class="form-label">Link</label>
                                                                    <input type="text" class="form-control" id="link" name="link" value="{{ $item->link }}">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <div class="mb-3">
                                                                        <label for="status" class="form-label">Status: </label></br/>
                                                                        <input type="hidden" name="status" value=""> 
                                                                        <input type="checkbox" name="status" id="switch{{ $item->quick_link_id }}" value="1"  {{ $item->status == 1 ? 'checked' : '' }}  data-switch="success" />  
                                                                        <label for="switch{{ $item->quick_link_id }}" data-on-label="" data-off-label=""></label>
                                                                    </div>                                                                                                                            
                                                                    </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Image</label><br>
                                                                    @if ($item->image)
                                                                    <img src="{{ env('APP_URL') . '/' . str_replace('public/', '', $item->image) }}" class="me-2 img-fluid avatar-xl" >
                                                                @else
                                                                    <span class="small text-danger">No Image</span>
                                                                @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Center Image</label>
                                                                    <div class="dropzone" id="ImageDropzone" data-plugin="dropzone">
                                                                        <div class="fallback">
                                                                            <input name="image" type="file" />
                                                                        </div>                             
                                                                        <div class="dz-message needsclick">
                                                                            <i class="h1 text-muted ri-upload-cloud-2-line"></i>
                                                                            <h4>Drop files here or click to upload.</h4>
                                                                        </div>
                                                                    </div>                             
                                                                    <!-- Preview -->
                                                                    <div class="dropzone-previews mt-3" id="file-previews"></div>
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
                                    <div id="delete-alert-modal{{ $item->quick_link_id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Quick Link?</p>
                                                        <form action="{{ route('quick-link.delete/{id}', $item->quick_link_id) }}" method="POST">
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
