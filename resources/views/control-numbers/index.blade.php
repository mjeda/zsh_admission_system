@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Control Number List</h1>
    </div>
    <div class="row">
        <div class="col">
            <form action="{{route('control-numbers')}}" class="form-inline mb-3">
                <input type="search" placeholder="Search by Control Number.." name="keyword" class="form-control">
                <input type="submit" value="Search" class="btn btn-primary ml-2">
            </form>
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Applicant & Admission</th>
                    <th>Control number</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created at</th>
                    @canany(['pay control number', 'extend time'])
                    <th>Action</th>
                    @endcanany
                </tr>
                </thead>
                <tbody>
                @forelse($control_numbers as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td> {{$item->admission?->full_name}}-
                            <a href="#">{{ $item->admission?->index_number }}</a>
                        </td>
                        <td>{{ $item->control_number }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->created_at }}</td>
                        @canany(['pay control number', 'extend time'])
                        <td>
                            @can('pay control number')
                                @if($item->status != 'PAID')
                                <a href="{{route('cash.payment', ['control_number' => $item->control_number, 'isCash'=>true])}}" class="btn btn-primary btn-sm" onclick="return confirm('Are You Sure?')">Mark as Paid</a>
                                @endif
                            @endcan
                            @can('extend time')
                                @if($item->status == 'CREATED')
                                <a href="{{route('control-number.extend-time', $item->id)}}" class="btn btn-warning btn-sm" onclick="return confirm('Are You Sure! Do you want to Extend time ?')">Extend time</a>
                                @endif
                            @endcan
                        </td>
                        @endcanany
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
            {{$control_numbers->links()}}
        </div>
    </div>
@endsection
