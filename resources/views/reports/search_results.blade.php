@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admission by Result Report</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <form action="{{route('report.search_results')}}" class="form-inline mb-3">
                        <div class="form-group">
                            <label class="mr-1">Subject Name:</label>
                            <input name="subject_name" class="form-control" value="{{request('subject_name')}}">
                        </div>

                        <div class="form-group">
                            <label class="mr-1 ml-2">Subject Grade:</label>
                            <input name="subject_grade" class="form-control" value="{{request('subject_grade')}}">
                        </div>

                        <div class="btn-group">
                            <input type="submit" value="Filter" class="btn btn-primary ml-2 pull">
                            <a href="{{ route('report.search_results') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
                @can('download result searched')
                    <div class="col-md-1">
                        <button type="button" class="btn btn-secondary float-right" id="print_btn">
                            Print
                        </button>
                    </div>
                @endcan
            </div>

            <div class="table-responsive" id="div_to_print">
{{--                {!! $notify !!}--}}
                <table class="table table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th>Admission</th>
                        <th>Full name</th>
                        <th>Index Number</th>
                        <th>Index Number 2</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                      $title = null;
                    @endphp
                      @forelse($admissions as $admission)
                          <tr>
                              <td><a href="{{route('admission.show', $admission->id)}}">View Admission</a> </td>
                              <td>{{$admission->first_name}} {{$admission->last_name}}</td>
                              <td>{{$admission->index_number}}</td>
                              <td>{{$admission->index_number_2}}</td>
                          </tr>
                      @empty
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
                // styles: ["{{asset("css/bootstrap.css")}}"]
                // exclude: ['.do_not_print', '.print_exclude' ]
                // also accept array input: exclude: [<array>]
                // you can specify id, class or even html element tag
            });
       });
    });
</script>
@endpush
