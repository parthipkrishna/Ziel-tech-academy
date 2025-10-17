@extends('layouts.dashboard')
@section('add-company-info')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">CompanyInfo</a></li>
                        <li class="breadcrumb-item active">Add CompanyInfo</li>
                    </ol>
                </div>
                <h4 class="page-title">Add CompanyInfo</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add CompanyInfo</h4>
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
                            <form class="needs-validation" id="CompanyInfoForm" method="POST" action="{{ route('admin.company.info.store') }}" enctype="multipart/form-data"  validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Mission</label>
                                            <textarea class="form-control" name="mission" id="mission" rows="3"></textarea>
                                            @error('mission')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Vision</label>
                                            <textarea class="form-control" name="vision" id="vision" rows="3"></textarea>
                                            @error('vision')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Why Choose Us</label>
                                            <textarea class="form-control" name="why_choose_us" id="why_choose_us" rows="3"></textarea>
                                            @error('why_choose_us')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div> -->

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Offerings</label>
                                            <textarea class="form-control" name="offerings" id="offerings" rows="3"></textarea>
                                            @error('offerings')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
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
