@extends('layouts.dashboard')
@section('list-company-info')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Company Info</a></li>
                        <li class="breadcrumb-item active">Company Info</li>
                    </ol>
                </div>
                <h4 class="page-title">Company Info</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <form class="needs-validation" id="CompanyInfoForm" method="POST" 
                        action="{{ isset($companyinfo) ? route('admin.company.info.update', $companyinfo->id) : route('admin.company.info.store') }}" 
                        enctype="multipart/form-data" validate>
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="mission" class="form-label">Mission</label>
                                    <textarea class="form-control" name="mission" id="mission" rows="3">{{ old('mission', $companyinfo->mission ?? '') }}</textarea>
                                    @error('mission')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="vision" class="form-label">Vision</label>
                                    <textarea class="form-control" name="vision" id="vision" rows="3">{{ old('vision', $companyinfo->vision ?? '') }}</textarea>
                                    @error('vision')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="offerings" class="form-label">Offerings</label>
                                    <textarea class="form-control" name="offerings" id="offerings" rows="3">{{ old('offerings', $companyinfo->offerings ?? '') }}</textarea>
                                    @error('offerings')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-start">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            @if(auth()->user()->hasPermission('company-info', 'edit'))
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($companyinfo) ? 'Update' : 'Save' }}
                                </button>
                            @endif
                        </div>
                    </form>      
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
