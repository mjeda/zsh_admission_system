@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Application List</h1>
    </div>
    <div class="row">
        <div class="col">
            <form action="{{route('admission.index')}}" class="form-inline mb-3">
                <input type="search" placeholder="Search by Anything.." name="keyword" class="form-control">
                <input type="submit" value="Search" class="btn btn-primary ml-2">
            </form>
            <table class="table table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Full name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>School name</th>
                    <th>Index Number</th>
                    <th>Created at</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($admissions as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $item->first_name ." ". $item->middle_name ." ". $item->last_name}}</td>
                        <td>{{ $item->gender }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->school_name }}</td>
                        <td>{{ $item->index_number }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <div class="float-right">
                                <a href="{{ route('admission.show', $item)  }}" class="btn btn-info btn-sm">Detail</a>
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
            {{ $admissions->links() }}
        </div>
    </div>
@endsection
