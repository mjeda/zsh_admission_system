@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Selection Report</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <form action="{{route('report.selection_by_course')}}" class="form-inline mb-3">
                        <div class="form-group">
                            <label class="mr-1">Intake:</label>
                            <select name="intake" class="form-control">
                                <option value="">--- Select ---</option>
                                @foreach($intakes as $intake)
                                    <option value="{{$intake->id}}" @if(request('intake') == $intake->id) selected @endif>{{$intake->title. " round - ". $intake->round .", ". $intake->year}}</option>
                                @endforeach
{{--                                <option value="ACCEPTED">Accepted by NACTE</option>--}}
{{--                                <option value="REJECTED">Rejected by NACTE</option>--}}
                            </select>
                        </div>
                        <div class="form-group ml-3">
                            <label class="mr-1">Course:</label>
                            <select name="course" class="form-control mx-2 @error('course') bg-dark text-light @enderror">
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{$course->id}}" @if(request('course') == $course->id) selected @endif>{{$course->name}}</option>
                                @endforeach
                            </select>
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
                        <th>Index Number</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>School name</th>
                        <th>Point</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($admissions as $item)
                        <tr>
                            <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                            <td>{{ $item->admission->first_name ." ". $item->admission->middle_name ." ". $item->admission->last_name}}</td>
                            <td>{{ $item->admission->index_number }}</td>
                            <td>{{ $item->admission->gender }}</td>
                            <td>{{ $item->admission->phone }}</td>
                            <td>{{ $item->admission->school_name }}</td>
                            <td>{{ $item->admission->points }}</td>
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
