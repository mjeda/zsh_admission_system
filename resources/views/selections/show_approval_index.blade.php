@extends('layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">View Approval Status</h1>
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
                    <th>Username</th>
                    <th>Multi Selection</th>
                    <th>Academic Year</th>
                    <th>Intake</th>
                    <th>Eligibility</th>
                    <th>Remark</th>
                    <th>Verification Status</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($submissions->params??[] as $item)
                        <tr>
                            <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                            <td>{{ $item->username }}}</td>
                            <td>{{ $item->multiple_selection }}</td>
                            <td>{{ $item->academic_year }}</td>
                            <td>{{ $item->intake }}</td>
                            <td>{{ $item->eligibility }}</td>
                            <td>{{ $item->remarks }}</td>
                            <td>{{ $item->verification_status }}</td>
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

