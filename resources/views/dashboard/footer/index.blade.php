@extends('layouts.dashboard')
@section('list-web-footer')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Ziel Tech</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Footer Section</a></li>
                        <li class="breadcrumb-item active">Footer Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Footer Section</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                        @if(!empty($footer))
                        <form class="needs-validation" id="WebBannerForm" method="POST" action="{{ route('admin.footer.update', $footer->id) }}" enctype="multipart/form-data" validate>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="validationCustom01">Title</label>
                                        <input type="text" name="title" value="{{ old('title', $footer->title ?? '') }}" 
                                            class="form-control" id="validationCustom01" placeholder="Title">
                                        @error('title')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="validationCustom01">Play Store</label>
                                        <input type="text" name="playstore" value="{{ old('playstore', $footer->playstore ?? '') }}" 
                                            class="form-control" id="validationCustom01" placeholder="Play Store">
                                        @error('playstore')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="validationCustom01">App Store</label>
                                        <input type="text" name="appstore" value="{{ old('appstore', $footer->appstore ?? '') }}" 
                                            class="form-control" id="validationCustom01" placeholder="App Store">
                                        @error('appstore')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="validationCustom01">Copy Right</label>
                                        <input type="text" name="copy_right" value="{{ old('copy_right', $footer->copy_right ?? '') }}" 
                                            class="form-control" id="validationCustom01" placeholder="Copy Right">
                                        @error('copy_right')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="example-textarea" class="form-label">Short Description</label>
                                        <textarea class="form-control" name="short_desc" id="short_desc" rows="3">{{ old('short_desc', $footer->short_desc ?? '') }}</textarea>
                                        @error('short_desc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image</label>
                                        <input type="file" name="footer_logo" class="form-control">
                                        @if(!empty($footer->footer_logo))
                                            <img src="{{ asset('storage/' . $footer->footer_logo) }}" width="100" class="mt-2">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-start">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    @else
                        <p class="text-center text-danger">No data available</p>
                    @endif
                        </div> <!-- end preview-->
                    </div> 
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
