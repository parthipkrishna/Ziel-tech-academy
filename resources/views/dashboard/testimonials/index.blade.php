@extends('layouts.dashboard')
@section('testimonials')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Testimonials</a></li>
                        <li class="breadcrumb-item active">Testimonials</li>
                    </ol>
                </div>
                <h4 class="page-title">Testimonials</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('student-testimonials', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-danger mb-2">
                                    <i class="mdi mdi-plus-circle me-2"></i> Add
                                </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                            <!-- Additional filters or search can go here -->
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="testimonials-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Content</th>
                                    <th>Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $testimonial)
                                    <tr>
                                        <td>{{ $testimonial->student->first_name }}</td> <!-- Assuming a relationship to fetch student name -->
                                        <td>{{ Str::limit($testimonial->content, 50, '...') }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $testimonial->rating)
                                                    <i class="mdi mdi-star text-warning"></i>
                                                @else
                                                    <i class="mdi mdi-star-outline text-warning"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('student-testimonials', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editTestimonial-modal{{ $testimonial->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('student-testimonials', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $testimonial->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="bs-editTestimonial-modal{{ $testimonial->id }}" tabindex="-1" role="dialog" aria-labelledby="editTestimonialLabel{{ $testimonial->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editTestimonialLabel{{ $testimonial->id }}">Edit Testimonial</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="student_id">Student</label>
                                                                    <select class="form-control" name="student_id" id="student_id" required >
                                                                    @foreach ($students as $student)
                                                                        <option value="{{ $student->id }}" {{ $testimonial->student_id == $student->id ? 'selected' : '' }}>
                                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="rating">Rating</label>
                                                                    <input type="number" name="rating" value="{{ $testimonial->rating }}" class="form-control" id="rating" min="1" max="5" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label for="content" class="form-label">Content</label>
                                                                    <textarea class="form-control" name="content" id="content" rows="4" required>{{ $testimonial->content }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <!-- Delete Alert Modal -->
                                    <div id="delete-alert-modal{{ $testimonial->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Testimonial?</p>
                                                        <form action="{{ route('admin.testimonials.delete',$testimonial->id) }}" method="POST">
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