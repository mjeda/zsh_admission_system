@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Payment List</h1>
    </div>
    <div class="row">
        <div class="col">
            <form action="{{route('payments')}}" class="form-inline mb-3">
                <input type="search" placeholder="Search by Anything.." name="keyword" class="form-control">
                <input type="submit" value="Search" class="btn btn-primary ml-2">
            </form>
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Applicant Name</th>
                    <th>Control number</th>
                    <th>Amount</th>
                    <th>Received By</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $payment->controlNumber->admission->first_name .' '. $payment->controlNumber->admission->middle_name .' '. $payment->controlNumber->admission->last_name }}</td>
                        <td>{{ $payment->controlNumber->control_number }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->user->name }}</td>
                        <td>{{ $payment->payment_date }}</td>
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
            {{$payments->links()}}
        </div>
    </div>
@endsection
