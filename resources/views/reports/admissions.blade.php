@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Applications Report</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <form action="{{route('report.admission')}}" class="form-inline mb-3">
                        <div class="form-group">
                            <label class="mr-1">Status:</label>
                            <select name="status" class="form-control">
                                <option value="">--- Select ---</option>
                                <option value="CREATED">Incomplete</option>
                                <option value="SUBMITTED">Completed</option>
                                <option value="SELECTED">Selected</option>
                                <option value="SUBMITTED">Submitted</option>
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
                            <a href="{{ route('report.admission') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
                @can('download admission report')
                <div class="col-md-1">
                    <button type="button" class="btn btn-secondary float-right" id="print_btn">
                        Print
                    </button>
                </div>
                @endcan
            </div>

            <div class="table-responsive" id="div_to_print">
                {!! $notify !!}
                <table class="table table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th width="35px" class="border-right">SN</th>
                        <th>Full name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Index Number</th>
                        <th>Selected Course</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($admissions as $item)
                        <tr>
                            <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                            <td>{{ $item->first_name ." ". $item->middle_name ." ". $item->last_name}}</td>
                            <td>{{ $item->gender }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->index_number }}</td>
                            <td>{{ $item->selection?->course?->name }}</td>
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
