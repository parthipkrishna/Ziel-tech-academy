@extends('layouts.dashboard')
@section('add-web-footer')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Ziel Tech</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Footer</a></li>
                        <li class="breadcrumb-item active">Add Footer</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Footer</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Footer</h4>
                    <div class="row justify-content-center">
                         {{-- Display general messages --}}
                         @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif
                        {{-- Display validation error messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger text-center w-75">
                                @foreach ($errors->all() as $error)
                                    <h6 class="text-center fw-bold">{{ $error }}</h6>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="WebBannerForm" method="POST" action="{{ route('admin.footer.store') }}" enctype="multipart/form-data"  validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Title</label>
                                            <input type="text" name="title" value="{{ old('title') }}" class="form-control" id="validationCustom01" placeholder="Title" >
                                            @error('title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Play Store</label>
                                            <input type="text" name="playstore" value="{{ old('playstore') }}" class="form-control" id="validationCustom01" placeholder="Play Store" >
                                            @error('playstore')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">App Store</label>
                                            <input type="text" name="appstore" value="{{ old('appstore') }}" class="form-control" id="validationCustom01" placeholder="App Store" >
                                            @error('appstore')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-lg-6">
                                        {{-- <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Slug</label>
                                            <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" id="validationCustom01" placeholder="Slug" >
                                            @error('slug')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Copy Right</label>
                                            <input type="text" name="copy_right" value="{{ old('copy_right') }}" class="form-control" id="validationCustom01" placeholder="Copy Write" >
                                            @error('copy_right')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Short Description</label>
                                            <textarea class="form-control" name="short_desc" id="short_desc" rows="3"></textarea>
                                            @error('short_desc')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Image</label>
                                            <input type="file" name="footer_logo" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->


@endsection
