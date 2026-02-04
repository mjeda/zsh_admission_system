@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Intake</h1>
    </div>
    <!-- Form --->
    @canany(['add intake', 'edit intake'])
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-muted font-weight-bold">@if($intake->exists) Edit @else Add @endif Intake</h5>
            </div>
            <div class="row">
                <div class="col">
                    <form action="@if($intake->exists) {{ route('intake.update', $intake) }} @else {{ route('intake.store') }} @endif" method="POST" class="pb-3">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-3 pt-3 pr-3 pl-3">
                                <label for="title" class="form-label">Intake</label>
                                <select class="form-select  @error('title') is-invalid @enderror" name="title" id="title">
                                    @if($intake->title == 'March')
                                        <option value="March">March</option>
                                        <option value="September">September</option>
                                    @else
                                        <option value="September">September</option>
                                        <option value="March">March</option>
                                    @endif
                                </select>
                                @error("title")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="round" class="form-label">Round</label>
                                <input type="number" value="{{ $intake->round }}" min="1" max="5" class="form-control  @error('round') is-invalid @enderror" name="round" id="round">
                                @error("round")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="year">Year</label>
                                <input type="text" value="{{ $intake->year }}" min="4" max="4" class="form-control  @error('year') is-invalid @enderror" name="year" id="year" placeholder="Year">
                                @error("year")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="type">Type</label>
                                <select class="form-control @error('type') is-invalid @enderror" name="type">
                                    <option value="{{ $intake->type ?? "" }}">{{ $intake->type ?? "--- Select type ---" }}</option>
                                    @if($intake->exists)
                                        @if($intake->type == "Diploma")
                                            <option value="VTA/VETA">VTA/VETA</option>
                                        @else
                                            <option value="Diploma">Diploma</option>
                                        @endif
                                    @else
                                        <option value="Diploma">Diploma</option>
                                        <option value="VTA/VETA">VTA/VETA</option>
                                    @endif
                                </select>
                                @error("type")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pt-3 pr-3 pl-3">
                                <label for="expire_date" class="form-label">End date</label>
                                <input type="date" value="{{ $intake->expire_date }}" min="{{date('Y-m-d')}}" class="form-control  @error('expire_date') is-invalid @enderror" name="expire_date" id="expire_date">
                                @error("expire_date")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 pt-3 pr-3">
                                <div class="btn-group float-right">
                                    <button type="submit" class="btn @if($intake->exists) btn-success @else btn-primary @endif" >
                                        @if($intake->exists) Edit @else Add @endif intake
                                    </button>
                                    @if($intake->exists)
                                        <a href="{{ route("intake.index") }}" class="btn btn-light">Cancel</a>
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
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th >Name</th>
                    <th class="text-center">Round</th>
                    <th>Year</th>
                    <th>Closed_date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                    <th width="200"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($intakes as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td class="text-center">{{ $item->round }}</td>
                        <td>{{ $item->year }}</td>
                        <td>{{ $item->expire_date }}</td>
                        <td>{{ $item->type }}</td>
                        <td>
                            @if($item->status == "Open")
                                <strong class="text-info ">Open</strong>
                            @else
                                <strong class="text-danger ">Close</strong>
                            @endif
                        </td>
                        <td>{{ date_format($item->created_at, 'd-m-y') }}</td>
                        <td>{{ date_format($item->updated_at, "d-m-y") }}</td>
                        <td>
                            <div class="btn-group float-right">
                                @can('change intake status')
                                    <a href="{{ route('intake.status', $item)  }}" class="btn {{ ($item->status == "Open") ? "btn-danger" : "btn-info" }}  btn-sm"
                                       onclick="return confirm('Are you sure! \nDo you want to {{ ($item->status == "Open") ? "Close" : "Open" }} ?')">
                                        {{ ($item->status == "Open") ? "Close" : "Open" }}
                                    </a>
                                @endcan
                                @can('edit intake')
                                <a href="{{ route('intake.index', $item)  }}" class="btn btn-success btn-sm">Edit</a>
                                @endcan
                                @can('delete intake')
                                <a href="{{ route('intake.delete', $item)  }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ! \nDo you want to delete ?')">Remove</a>
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
            {{ $intakes->links() }}
        </div>
    </div>
@endsection
