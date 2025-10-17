@extends('layouts.dashboard')
@section('list-contact-info')

  <!-- start page title -->
  <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Contact Info</a></li>
                        <li class="breadcrumb-item active">Contact Info</li>
                    </ol>
                </div>
                <h4 class="page-title">Contact Info</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <form action="{{ isset($contact) ? route('admin.contacts.update', $contact->id) : route('admin.contacts.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $contact->email ?? '') }}" class="form-control" id="email" 
                                    placeholder="Enter Email">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Contact No.</label>
                                <input type="text" name="phone" value="{{ old('phone', $contact->phone ?? '') }}" class="form-control" id="phone" 
                                    placeholder="Enter Contact Number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="address" rows="2" 
                                    placeholder="Enter Address">{{ old('address', $contact->address ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="google_map_link" class="form-label">Google Map Link</label>
                                <input type="text" name="google_map_link" value="{{ old('google_map_link', $contact->google_map_link ?? '') }}" class="form-control" id="google_map_link" 
                                    placeholder="Enter Google Map Link">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex mt-3">
                        @if(auth()->user()->hasPermission('contact-info', 'edit'))
                            <button type="submit" class="btn btn-primary me-2">
                                {{ isset($contact) ? 'Update' : 'Save' }}
                            </button>
                        @endif
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection