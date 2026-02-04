@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Courses</h1>
    </div>
    <!-- Form --->
    @canany(['add course','edit course'])
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-muted font-weight-bold">@if($course->exists) Edit @else Add @endif Course</h5>
            </div>
            <div class="row">
                <div class="col">
                    <form action="@if($course->exists) {{ route('course.update', $course) }} @else {{ route('course.store') }} @endif" method="POST" class="pb-3">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 pt-3 pr-3 pl-3">
                                <label for="code">Course Code</label>
                                <input type="text" value="{{ $course->code }}" class="form-control  @error('code') is-invalid @enderror" name="code" id="code" placeholder="Code">
                                @error("code")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="tag">Course Tag</label>
                                <input type="text" value="{{ $course->tag }}" class="form-control  @error('tag') is-invalid @enderror" name="tag" id="tag" placeholder="Tag">
                                @error("tag")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="type">Course Type</label>
                                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="{{ $course->type ?? "" }}">{{ $course->type ?? "--- Select type ---" }}</option>
                                    @if($course->exists)
                                        @if($course->type == "Diploma")
                                            <option value="VTA/VETA">VTA/VETA</option>
                                        @else
                                            <option value="Diploma">Diploma</option>
                                        @endif
                                    @else
                                        <option value="Diploma">Diploma</option>
                                        <option value="VTA/VETA">VTA/VETA</option>
                                    @endif
                                </select>
                                @error("filtered")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="passes">Number of Passes</label>
                                <input type="number" value="{{ $course->passes }}" class="form-control @error('passes') is-invalid @enderror" name="passes" id="passes" placeholder="Passes">
                                @error("passes")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6 pt-3 pr-3 pl-3">
                                <label for="name">Course Name</label>
                                <input type="text" value="{{ $course->name }}" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Name">
                                @error("name")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6 pt-3 pr-3 pl-3">
                                <label for="filtered">Subjects</label>
                                <select id="filtered" name="filtered" class="form-select @error('filtered') is-invalid @enderror">
                                    <option>{{$course->filtered}}</option>
                                    <option>Any except excluded in criteria</option>
                                    <option>From Criteria except excluded</option>
                                </select>
                                @error("filtered")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row  pt-3 pr-4">
                            <div class="col">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm @if($course->exists) btn-success @else btn-primary @endif" >
                                        @if($course->exists) Edit @else Add @endif course
                                    </button>
                                    @if($course->exists)
                                        <a href="{{ route("course.index") }}" class="btn btn-light btn-sm">Cancel</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcanany
    <!-- List --->
    <div class="row">
        <div class="col">
            <table class="table table-responsive table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th width="450">Course Name</th>
                    <th>Tag</th>
                    <th>Type</th>
                    <th class="text-nowrap">Subjects</th>
                    <th>Status</th>
                    <th>Passes</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                    <th width="200"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($courses as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td class="text-nowrap">{{ $item->name }}</td>
                        <td>{{ $item->tag }}</td>
                        <td class="text-nowrap">{{ $item->type }}</td>
                        <td class="text-nowrap">{{ $item->filtered }}</td>
                        <td>
                            @if($item->status == "Open")
                                <strong class="text-info ">Open</strong>
                            @else
                                <strong class="text-danger ">Close</strong>
                            @endif
                        </td>
                        <td>{{ $item->passes }}</td>
                        <td>{{ date_format($item->created_at, 'd-m-y') }}</td>
                        <td>{{ date_format($item->updated_at, 'd-m-y') }}</td>
                        <td>
                            <div class="btn-group float-right">
                                @can('change course status')
                                    <a href="{{ route('course.status', $item)  }}" class="btn {{ ($item->status == "Open") ? "btn-danger" : "btn-info" }}  btn-sm"
                                       onclick="return confirm('Are you sure! \nDo you want to {{ ($item->status == "Open") ? "Close" : "Open" }} ?')">
                                        {{ ($item->status == "Open") ? "Close" : "Open" }}
                                    </a>
                                @endcan
                                @can('view criteria')
                                    <a href="{{ route('criteria.index', $item)  }}" class="btn btn-warning btn-sm">Criteria</a>
                                @endcan
                                @can('edit course')
                                <a href="{{ route('course.index', $item)  }}" class="btn btn-success btn-sm">Edit</a>
                                @endcan
                                @can('delete course')
                                <a href="{{ route('course.delete', $item)  }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ! \nDo you want to delete ?')">Remove</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">
                        No content in this table
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
            {{ $courses->links() }}
        </div>
    </div>
@endsection
