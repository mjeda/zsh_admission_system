@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Payment Report</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <form action="{{route('report.payment')}}" class="form-inline mb-3">
                        <div class="form-group">
                            <label class="mr-1">Method:</label>
                            <select name="method" class="form-control">
                                <option value="">--- Select ---</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>
                        <div class="form-group ml-3">
                            <label class="mr-1">Start Date:</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group ml-3">
                            <label class="mr-1">End Date:</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>

                        <div class="btn-group">
                            <input type="submit" value="Filter" class="btn btn-primary ml-2 pull">
                            <a href="{{ route('report.payment') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
                @can('download payment report')
                    <div class="col-md-1">
                        <button type="button" class="btn btn-secondary float-right" id="print_btn">
                            Print
                        </button>
                    </div>
                @endcan
            </div>

            <div class="table-responsive" id="div_to_print">
                {!! $notify !!}
                <table class="table  table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th width="35px" class="border-right">SN</th>
                        <th>Control number</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Received By</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                            <td>{{ $payment->controlNumber->control_number }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td class="text-capitalize">{{ $payment->user->name }}</td>
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
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="font-weight-bold">TZS {{ $payments->sum('amount') }}/=</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('printThis/jquery.printThis.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(e) {
           $('button#print_btn').on('click', function(e)  {
                $('#div_to_print').printThis({
                    styles: ["{{asset("css/bootstrap.css")}}"]
                    // exclude: ['.do_not_print', '.print_exclude' ]
                    // also accept array input: exclude: [<array>]
                    // you can specify id, class or even html element tag
                });
           });
        });
    </script>
@endpush
