@extends('lms.layout.layout')
@section('add-batches')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Students</a></li>
                        <li class="breadcrumb-item active">Add Batches</li>
                    </ol>
                </div>
                {{-- <h4 class="page-title">Add Batches</h4> --}}
            </div>
        </div>
    </div>
    <!-- end page title -->  

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add batches</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                                            
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="StudentForm" method="POST" action="{{ route('lms.store.batch') }}" enctype="multipart/form-data"  novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name"  value="{{ old('name') }}" class="form-control"  id="name"  placeholder="Enter Name" required>
                                            @error('name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="batch_number" class="form-label">Batch Number</label>
                                            <input type="text" name="batch_number"  value="{{ old('batch_number') }}" class="form-control"  id="batch_number"  placeholder="Enter Batch Number" required>
                                            @error('batch_number')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">QC</label>                                                 
                                            <select class="select2 form-control select2-multiple" name="qc_ids[]" multiple data-placeholder="Choose QC">
                                                <optgroup label="Select QCs for the Batches">
                                                    @foreach ($qc_list as $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                    @endforeach                                               
                                                </optgroup>
                                            </select>                                           
                                        </div>
                                    </div>  

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="student_limit" class="form-label">Student Limit</label>
                                            <input type="text" name="student_limit"  value="{{ old('student_limit') }}" class="form-control"  id="student_limit"  placeholder="Enter Student Limit"  >
                                            @error('student_limit')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="tutor_id" class="form-label">Tutor</label>
                                            <select name="tutor_id" class="form-control">
                                                <option value="">Choose a Tutor</option>
                                                @foreach($tutor_list as $tutor)
                                                    <option value="{{ $tutor['id'] }}">{{ $tutor['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="course_id" class="form-label">Course</label>
                                            <select name="course_id" class="form-control" required>
                                                <option value="">Choose a Course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <p class="small text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label></br/>
                                            <!-- Always send 0 if checkbox is unchecked -->
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" id="switch3" value="1" checked data-switch="success">
                                            <label for="switch3" data-on-label="" data-off-label=""></label>
                                        </div>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-sm-5">    
                                                        <h4 class="header-title mb-3">Batch Channels</h4> 
                                                    </div>
                                                    <div class="col-sm-7" style= "text-align: right;">
                                                        <a href="#" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                                            <i class="mdi mdi-plus-circle me-2"></i> Add 
                                                          </a>
                                                    </div><!-- end col-->
                                                </div>
                                                <div class="tab-content">
                                                    <div class="tab-pane show active" id="custom-styles-preview2">
                                                    
                                                    </div> <!-- end preview-->
                                                </div> <!-- end tab-content-->
                                            </div> <!-- end card-body -->
                                        </div> <!-- end card -->
                                    </div><!-- end col -->
                                </div><!-- end row -->


  
                                <!-- Submit Button -->
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

    <!-- Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm">
                        <div class="row">

                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="group_name" class="form-label">Group Name</label>
                                <input type="text" name="group_name"  value="{{ old('group_name') }}" class="form-control"  id="group_name"  placeholder="Enter Group Name" required>
                                @error('group_name')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="admin_id" class="form-label">Admin</label>
                                <select name="admin_id" class="form-control" required>
                                    <option value="">Choose a Admin</option>
                                    @foreach($admin_list as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="batch_status" class="form-label">Status: </label></br/>
                                <input  type="checkbox" name="batch_status"  id="switch4"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                <label for="switch4" data-on-label="" data-off-label=""></label>
                            </div>

                        </div>

                       
                        <!-- Add more fields if needed -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveItem">Save</button>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        document.getElementById('saveItem').addEventListener('click', function () {
            const type = document.querySelector('[name="type"]').value.trim();
            const typeLabel = document.querySelector('[name="type"] option:checked').textContent;
    
            const groupName = document.getElementById('group_name').value.trim();
            const adminId = document.querySelector('[name="admin_id"]').value;
            const adminLabel = document.querySelector('[name="admin_id"] option:checked').textContent;
    
            const statusCheckbox = document.querySelector('[name="batch_status"]'); // ✅ Fixed selector
            const batchStatus = statusCheckbox && statusCheckbox.checked ? '1' : '0';
    
            if (type && groupName) {
                const previewContainer = document.getElementById('custom-styles-preview2');
    
                const item = document.createElement('div');
                item.className = 'alert alert-secondary d-flex justify-content-between align-items-center mt-2';
                const itemId = Date.now(); // unique ID for removing later
                item.dataset.id = itemId;
    
                item.innerHTML = `
                    <div>
                        <strong>Type:</strong> ${typeLabel}<br>
                        <strong>Group Name:</strong> ${groupName}<br>
                        <strong>Admin:</strong> ${adminId ? adminLabel : 'N/A'}<br>
                        <strong>Status:</strong> ${batchStatus === '1' ? 'Active' : 'Inactive'}
                    </div>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                `;
    
                // Remove item and related hidden inputs
                item.querySelector('.btn-close').addEventListener('click', function () {
                    item.remove();
                    document.querySelectorAll(`[data-input-id="${itemId}"]`).forEach(el => el.remove());
                });
    
                previewContainer.appendChild(item);
    
                const form = document.getElementById('StudentForm');
                const channelCount = previewContainer.children.length - 1;
    
                // Create hidden inputs
                const fields = {
                    type: type,
                    group_name: groupName,
                    admin_id: adminId,
                    batch_status: batchStatus // ✅ renamed from "status"
                };
    
                for (const [key, value] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `channels[${channelCount}][${key}]`;
                    input.value = value;
                    input.setAttribute('data-input-id', itemId); // so we can delete this later
                    form.appendChild(input);
                }
    
                // Reset modal
                document.getElementById('addItemForm').reset();
                const modalEl = document.getElementById('addItemModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalInstance.hide();
            } else {
                alert("Please fill in the required fields: Type and Group Name.");
            }
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("StudentForm");
            const resetBtn = form.querySelector("button[type='reset']");
            resetBtn.addEventListener("click", function (e) {
                setTimeout(() => {
                    $('.select2').val(null).trigger('change');
                    document.querySelector('#custom-styles-preview2').innerHTML = '';
                }, 0);
            });
        });
        $(document).ready(function () {
            $('#StudentForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                // reset modal messages
                $('#modal-error-list').html('');
                $('#modal-success-message').html('');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('lms.store.batch') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-success-message').text(response.message);
                        let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                        successModal.show();
                        $('#StudentForm')[0].reset();
                        setTimeout(() => {
                                            window.location.href = "{{ route('lms.batches') }}";
                                        }, 1500);
                                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '<ul class="list-unstyled text-start">';
                            $.each(errors, function (field, messages) {
                                errorHtml += '<li>' + messages[0] + '</li>';
                            });
                            errorHtml += '</ul>';
                            $('#modal-error-list').html(errorHtml);
                        } else {
                            $('#modal-error-list').html('<p>' + (xhr.responseJSON.message || 'Something went wrong') + '</p>');
                        }
                        let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                        errorModal.show();
                    }
                });
            });
        });
    </script>
           
@endsection 
