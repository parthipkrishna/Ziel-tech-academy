@extends('lms.layout.layout')
@section('edit-batches')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Students</a></li>
                        <li class="breadcrumb-item active">Edit Batches</li>
                    </ol>
                </div>
                {{-- <h4 class="page-title">Edit Batches</h4> --}}
            </div>
        </div>
    </div>
    <!-- end page title -->  

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Edit batches</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                    {{-- @foreach ($batchDetails as $batch)  --}}
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form id="StudentForm" novalidate action="{{ route('lms.update.batch', $batch->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name"  value="{{ $batch->name }}" class="form-control"  id="name"  placeholder="Enter Name" >
                                        </div>   
                                        <div class="mb-3">
                                            <label for="student_limit" class="form-label">Student Limit</label>
                                            <input type="text" name="student_limit"  value="{{ $batch->student_limit }}" class="form-control"  id="student_limit"  placeholder="Enter Student Limit" >
                                        </div> 
                                        
                                        <div class="mb-3">
                                            <label class="form-label" for="qc_ids[]">QCs</label>
                                            <select class="select2 form-control select2-multiple" name="qc_ids[]" multiple data-placeholder="Choose QC">
                                                <optgroup label="Select QCs for the Batches">
                                                    @foreach ($qc_list as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ in_array($item['id'], old('qc_ids', $batch->qc_ids ?? [])) ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach                                               
                                                </optgroup>
                                            </select>
                                        </div>
                                                                                                        
                                    </div>                         
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="batch_number" class="form-label"> Batch Number</label>
                                            <input type="text" name="batch_number"  value="{{ $batch->batch_number }}" class="form-control"  id="batch_number"  placeholder="Enter Batch Number" >
                                        </div> 

                                        <div class="mb-3">
                                            <label class="form-label" for="tutor_id">Tutor</label>
                                            <select class="form-select" id="example-select" name="tutor_id">
                                                <option value="">Select Tutor</option>
                                                @foreach ($tutor_list as $item)
                                                    <option value="{{ $item['id'] }}"
                                                        {{ (old('tutor_id') ?? ($batch->tutor_id ?? '')) == $item['id'] ? 'selected' : '' }}>
                                                        {{ $item['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="course_id" class="form-label">Course</label>
                                            <select name="course_id" class="form-select" id="course_id" required>
                                                <option value="">Select Course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}"
                                                        {{ (old('course_id') ?? $batch->course_id) == $course->id ? 'selected' : '' }}>
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <p class="small text-danger">{{ $message }}</p>
                                            @enderror
                                        </div> 
                                        <div class="mb-3">
                                            <label for="status_{{  $batch->id }}" class="form-label">Status: </label></br/>
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" id="status_{{  $batch->id }}" value="1"  {{  $batch->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                            <label for="status_{{  $batch->id }}" data-on-label="" data-off-label=""></label>
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
                                                        <a href="#" class="btn btn-danger mb-2 open-add-modal">
                                                            <i class="mdi mdi-plus-circle me-2"></i> Add 
                                                        </a>
                                                    </div><!-- end col-->
                                                </div>
                                                <div class="tab-content">
                                                    <div class="tab-pane show active" id="custom-styles-preview2">
                                                        @php
                                                            $channels = $batchChannelsGrouped[$batch->id] ?? collect();
                                                        @endphp
                                                    
                                                        @if($channels->isEmpty())
                                                            <p class="text-muted">No channels for this batch.</p>
                                                        @else
                                                            <ul class="list-group">
                                                                @foreach($channels as $channel)
                                                                    <li class="list-group-item d-flex justify-content-between align-items-start channel-item" id="channel-item-{{ $channel->id }}">
                                                                        <div class="me-auto">
                                                                            <strong>{{ $types[$channel->type] ?? $channel->type }}</strong><br>
                                                                            <small>Group: {{ $channel->group_name }}</small><br>
                                                                            <small>Admin: {{ optional($channel->admin)->name ?? 'N/A' }}</small><br/>
                                                                            <small>Status:
                                                                                <span class="badge bg-{{ $channel->status ? 'success' : 'danger' }}">
                                                                                    {{ $channel->status ? 'Active' : 'Inactive' }}
                                                                                </span>
                                                                            </small>
                                                                        </div>

                                                                        <button type="button"
                                                                                class="btn btn-sm btn-outline-danger ms-2 delete-channel"
                                                                                data-id="{{ $channel->id }}"
                                                                                data-url="{{ route('lms.delete.batch.channel', $channel->id) }}">
                                                                            <i class="mdi mdi-delete"></i>
                                                                        </button>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                    <!-- end preview-->
                                                </div> <!-- end tab-content-->
                                            </div> <!-- end card-body -->
                                        </div> <!-- end card -->
                                    </div><!-- end col -->
                                </div><!-- end row -->
                                @if(empty($channels))
                                    <input type="hidden" name="channels" value="[]" />
                                @endif

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                    {{-- @endforeach --}}
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
        $(document).on('click', '.delete-channel', function (e) {
            e.preventDefault();

            const button = $(this);
            const channelId = button.data('id');
            const url = button.data('url');

            if (confirm('Are you sure you want to delete this channel?')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'POST',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#channel-item-' + channelId).fadeOut(300, function () {
                                $(this).remove();
                            });
                        } else {
                            alert('Could not delete channel.');
                        }
                    },
                    error: function (xhr) {
                        console.error('❌ Error deleting channel:', xhr.responseText);
                        alert('Failed to delete the channel. Please try again.');
                    }
                });
            }
        });
    </script>

    <script>
        let channelIndex = document.querySelectorAll('#custom-styles-preview2 .alert').length;
    
        $(document).on('click', '.open-add-modal', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('addItemModal'));
            modal.show();
        });
    
        document.getElementById('saveItem').addEventListener('click', function () {
            const type = document.querySelector('[name="type"]').value.trim();
            const typeLabel = document.querySelector('[name="type"] option:checked').textContent;
            const groupName = document.getElementById('group_name').value.trim();
            const adminId = document.querySelector('[name="admin_id"]').value;
            const adminLabel = document.querySelector('[name="admin_id"] option:checked').textContent;
            const statusCheckbox = document.querySelector('[name="batch_status"]');
            const batchStatus = statusCheckbox && statusCheckbox.checked ? '1' : '0';
    
            if (!type || !groupName) {
                alert("Please fill in the required fields: Type and Group Name.");
                return;
            }
    
            const previewContainer = document.getElementById('custom-styles-preview2');
            const form = document.getElementById('StudentForm');
            const itemId = Date.now();
    
            const item = document.createElement('div');
            item.className = 'alert alert-secondary d-flex justify-content-between align-items-center mt-2';
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
    
            item.querySelector('.btn-close').addEventListener('click', function () {
                item.remove();
                document.querySelectorAll(`[data-input-id="${itemId}"]`).forEach(el => el.remove());
            });
    
            previewContainer.appendChild(item);
    
            const fields = { type, group_name: groupName, admin_id: adminId, batch_status: batchStatus };
            Object.entries(fields).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `channels[${channelIndex}][${key}]`;
                input.value = value;
                input.dataset.inputId = itemId;
                form.appendChild(input);
            });

            channelIndex++;
    
            document.getElementById('addItemForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
        });
        document.getElementById('StudentForm').addEventListener('submit', function (e) {
            console.log('Form Data:', $(this).serializeArray());
        });

        document.getElementById('StudentForm').addEventListener('submit', function (e) {
            console.log('✅ Form submitting...');
            console.log($(this).serializeArray());
        });
    </script>
               
@endsection 

