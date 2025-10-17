@extends('layouts.dashboard')
@section('list-event')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Events</a></li>
                        <li class="breadcrumb-item active">Events</li>
                    </ol>
                </div>
                <h4 class="page-title">Events</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('event', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.event.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name </th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->name, 25, '...')  }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }} </td>
                                        <td>
                                            @if ($event->location)
                                                {{ Str::limit($event->location, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No location</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if ($event->description)
                                                {{ Str::limit($event->description, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No description</span>
                                            @endif 
                                        </td>
                                        
                                        <td>
                                            @if(auth()->user()->hasPermission('event', 'edit'))
                                                <a href="{{ route('admin.event.view', $event->id ) }}" class="action-icon"> <i class="mdi mdi-eye"></i></a>
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editEvent-modal{{ $event->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('event', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $event->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editEvent-modal{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="editEventLabel{{ $event->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editEventLabel{{ $event->id }}">Edit Event</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Name</label>
                                                                    <input type="text" name="name" value="{{ $event->name }}" class="form-control" id="validationCustom01" placeholder="Name" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom02">date</label>
                                                                    {{-- <input type="date" name="date" value="{{ $event->date }}" class="form-control" id="validationCustom02" placeholder="date" > --}}
                                                                    <input type="date" name="date" value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d') }}" class="form-control" id="validationCustom02">

                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom03">location</label>
                                                                    <input type="text" name="location" value="{{ $event->location }}" class="form-control" id="validationCustom03" placeholder="location" >
                                                                </div>                        
                                                            </div>
                        
                                                            <div class="col-lg-6">                        
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Description</label>
                                                                    <textarea class="form-control" name="description" id="description" rows="4">{{ $event->description }}</textarea>
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
                                    <div id="delete-alert-modal{{ $event->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Event?</p>
                                                        <form action="{{ route('admin.event.delete', $event->id) }}" method="POST">
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
