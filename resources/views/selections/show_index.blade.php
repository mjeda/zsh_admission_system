@extends('layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">View Submission</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between mb-2">
                    <a href="{{ url()->previous() }}"  class="btn btn-success ml-2"> Back to Collection</a>

            </div>

            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Full name</th>
                    <th>Index Number</th>
                    <th>Intake</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($submissions->params??[] as $item)
                        <tr>
                            <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                            <td>{{ $item->firstname }} {{ $item->secondname }} {{ $item->surname }}</td>
                            <td>{{ $item->indexnumber }}</td>
                            <td>{{ $item->intake }}</td>
                            <td>{{ $item->submission_status }}</td>
                            <td>{{ $item->update_status }}</td>
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
        </div>
    </div>
@endsection

