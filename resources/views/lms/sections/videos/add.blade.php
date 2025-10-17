@extends('lms.layout.layout')
@section('add-banners')

<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Videos</a></li>
                    <li class="breadcrumb-item active">Add Video</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Video Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Add Video</h4>

                @if ($message = session()->get('message'))
                    <div class="alert alert-success text-center w-75">
                        <h6 class="fw-bold">{{ $message }}</h6>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-center w-75">
                        @foreach ($errors->all() as $error)
                            <h6 class="fw-bold">{{ $error }}</h6>
                        @endforeach
                    </div>
                @endif

                <form id="VideoForm" method="POST" action="{{ route('lms.videos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Enter video title">
                            @error('title')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Video File</label>
                            <input type="file" id="video-file" class="file-input form-control">
                            <div class="text-start mt-2">
                                <button type="button" onclick="uploadChunkedVideo()" id="start-btn" class="btn btn-primary">Upload Video</button>
                                <button type="button" onclick="pauseUpload()" id="pause-btn" class="btn btn-warning d-none">Pause</button>
                                <button type="button" onclick="resumeUpload()" id="resume-btn" class="btn btn-success d-none">Resume</button>
                            </div>

                            <!-- Progress Bar -->
                            <div class="progress mt-2" style="height: 20px; display: none;" id="upload-progress-wrapper">
                                <div id="upload-progress-bar" class="progress-bar" role="progressbar"
                                    style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                            <div id="upload-status-message" class="mt-2 text-success fw-bold" style="display: none;"></div>
                            <!-- Hidden field to store uploaded video path -->
                            <input type="hidden" name="video_path" id="video_path" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control">
                            @error('thumbnail')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                            @error('order')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                       <div class="col-lg-6 mb-3">
                            <label class="form-label" for="is_enable">Status</label><br/>
                            <input type="checkbox" name="is_enable" id="switch_is_enable" value="1" checked data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                            <label for="switch_is_enable" data-on-label="" data-off-label=""></label>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Enter description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="text-start">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
let isPaused = false;
let currentChunkIndex = 0;
let resumeUploadId = null;
let resumeOriginalFileName = null;
let resumeFile = null;
let resumeTotalChunks = 0;

async function uploadChunkedVideo() {
    const fileInput = document.getElementById('video-file');
    const file = fileInput.files[0];
    if (!file) return alert("Select a video file");

    const chunkSize = 5 * 1024 * 1024; // 5MB
    const totalChunks = Math.ceil(file.size / chunkSize);
    const uploadId = `${file.name}-${Date.now()}`;
    const originalFileName = file.name;

    // Save global states
    resumeFile = file;
    resumeUploadId = uploadId;
    resumeOriginalFileName = originalFileName;
    resumeTotalChunks = totalChunks;
    currentChunkIndex = 0;
    isPaused = false;

    // UI updates
    document.getElementById("upload-progress-wrapper").style.display = "block";
    document.getElementById("upload-progress-bar").style.width = "0%";
    document.getElementById("upload-progress-bar").innerText = "0%";
    document.getElementById("upload-status-message").style.display = "none";
    document.getElementById('pause-btn').classList.remove('d-none');
    document.getElementById('resume-btn').classList.add('d-none');
    document.getElementById('start-btn').disabled = true;

    await uploadChunks(chunkSize);
}

async function uploadChunks(chunkSize) {
    while (currentChunkIndex < resumeTotalChunks) {
        if (isPaused) return;

        const start = currentChunkIndex * chunkSize;
        const end = Math.min(resumeFile.size, start + chunkSize);
        const chunk = resumeFile.slice(start, end);

        const formData = new FormData();
        formData.append("chunk", chunk);
        formData.append("upload_id", resumeUploadId);
        formData.append("chunk_index", currentChunkIndex);
        formData.append("total_chunks", resumeTotalChunks);
        formData.append("original_name", resumeOriginalFileName);

        let response;
        try {
            response = await fetch("{{ route('lms.videos.chunk.upload') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            });
        } catch (error) {
            alert("Chunk upload failed. Try resuming.");
            return;
        }

        const result = await response.json();

       if (result.file_name) {
            // Force progress bar to 100%
            const progressBar = document.getElementById("upload-progress-bar");
            progressBar.style.width = "100%";
            progressBar.innerText = "100%";
            progressBar.setAttribute("aria-valuenow", 100);

            document.getElementById("video_path").value = result.file_name;

            const messageDiv = document.getElementById("upload-status-message");
            messageDiv.innerText = "Upload completed successfully!";
            messageDiv.style.display = "block";

            document.getElementById('pause-btn').classList.add('d-none');
            document.getElementById('resume-btn').classList.add('d-none');
            document.getElementById('start-btn').disabled = false;

            return;
        }

        // Update progress
        const progressPercent = Math.round(((currentChunkIndex + 1) / resumeTotalChunks) * 100);
        const progressBar = document.getElementById("upload-progress-bar");
        progressBar.style.width = progressPercent + "%";
        progressBar.innerText = progressPercent + "%";
        progressBar.setAttribute("aria-valuenow", progressPercent);

        currentChunkIndex++;
    }
}


function pauseUpload() {
    isPaused = true;
    document.getElementById('pause-btn').classList.add('d-none');
    document.getElementById('resume-btn').classList.remove('d-none');
}

function resumeUpload() {
    isPaused = false;
    document.getElementById('pause-btn').classList.remove('d-none');
    document.getElementById('resume-btn').classList.add('d-none');
    uploadChunks(5 * 1024 * 1024); // Resume with 5MB
}
</script>


<script>
    $(document).ready(function () {
        var validator = $("#VideoForm").validate({
            rules: {
                title: { required: true, minlength: 3 },
            },
            messages: {
                title: {
                    required: "Video title is required",
                    minlength: "Title must be at least 3 characters"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            ignore: "" 
        });

        $("#video-file").rules("add", {
            required: true,
            messages: {
                required: "Please upload a video file"
            }
        });

        $("#video-file").on("change", function () {
            $(this).valid();
        });

        $("#start-btn").click(function (event) {
            if ($("#VideoForm").valid()) {
                uploadChunkedVideo();
            }
        });
    });
    $(document).ready(function () {
    $('#VideoForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        // clear previous errors
        $('#modal-error-list').html('');
        $('#modal-success-message').html('');
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            url: "{{ route('lms.videos.store') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-success-message').text(response.message);
                let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                successModal.show();
                $('#VideoForm')[0].reset();
                 setTimeout(() => {
                                    window.location.href = "{{ route('lms.videos.index') }}";
                                }, 1500);
                $('#upload-progress-bar').css('width', '0%').text('0%');
                $('#upload-progress-wrapper').hide();
            },
            error: function (xhr) {
                let errorHtml = '';
                let modalTitle = 'An Unexpected Error Occurred!';

                if (xhr.status !== 422) { 
                    let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong. Please check the logs.';
                    errorHtml = '<p>' + errorMsg + '</p>';
                    console.error(xhr.responseText);

                    $('#danger-alert-modal .modal-body h4').text(modalTitle);
                    $('#modal-error-list').html(errorHtml);
                    let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                    errorModal.show();
                }
            }
        });
    });
});

</script>

@endsection
