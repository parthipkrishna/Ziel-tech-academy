@extends('layouts.dashboard')
@section('list-campus')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Campus</a></li>
                            <li class="breadcrumb-item active">Campus</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Campus</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        </div>
                        <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap">
                            @if ($campuses->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="alert alert-warning">
                                            No Campus Found
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @foreach ($campuses as $campus)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <form action="{{ route('admin.campuses.update', $campus->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Home Tour</strong></label>
                                                    <textarea class="form-control" name="home_tour">{{ old('home_tour', $campus->home_tour ?? '') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Home Tour Image</strong></label>
                                                    <div class="mb-2">
                                                        <input type="file" class="form-control" name="home_tour_image">
                                                        @if (!empty($campus->home_tour_image))
                                                            <img src="{{ asset('storage/' . $campus->home_tour_image) }}" alt="Home Tour" width="80">
                                                        @else
                                                            <p>N/A</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Description</strong></label>
                                                    <textarea class="form-control" name="desc">{{ old('desc', $campus->desc ?? '') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Short Description</strong></label>
                                                    <input type="text" class="form-control" name="short" value="{{ old('short', $campus->short ?? '') }}">
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="mdi mdi-content-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            </table>

                    <!-- Always show an empty form if no campuses exist -->
                    @if ($campuses->isEmpty())
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.campuses.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label"><strong>Home Tour</strong></label>
                                            <textarea class="form-control" name="home_tour"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><strong>Home Tour Image</strong></label>
                                            <input type="file" class="form-control" name="home_tour_image">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><strong>Description</strong></label>
                                            <textarea class="form-control" name="desc"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><strong>Short Description</strong></label>
                                            <input type="text" class="form-control" name="short">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="mdi mdi-content-save"></i> Add Campus
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
