@extends('layouts.app')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admission Selection</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-start mb-2">
                <form class="form-inline">
                    <select name="intake" class="form-control mr-2 @error('intake') bg-dark text-light @enderror select-2">
                        <option value="">-- Select Intake --</option>
                        @forelse($intakes as $intake)
                            <option value="{{$intake->id}}" @if(request('intake') == $intake->id) selected @endif>{{$intake->type. ' - '. $intake->title. " round ". $intake->round .", ". $intake->year}}</option>
                        @empty
                        @endforelse
                    </select>
                    <select name="course" class="form-control mx-2 @error('course') bg-dark text-light @enderror">
                        <option value="">-- Select Course --</option>
                        @forelse($courses as $course)
                            <option value="{{$course->id}}" @if(request('course') == $course->id) selected @endif>{{$course->name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <select name="choice" class="form-control mr-2 @error('choice') bg-dark text-light @enderror">
                       <option value="">-- Select Choice --</option>
                       <option value="1"> First Choice</option>
                       <option value="2"> Second Choice</option>
                       <option value="3"> Third Choice</option>
                   </select>

                    <input type="submit" value="Filter by Course" class="btn btn-success">

                </form>
                @if(request('course') && request('intake'))
                @can('collect selected')
                  <a href="#" id="collect" class="btn btn-primary ml-2"> Collect Selected</a>
                @endcan
                @can('view collection')
                  <a href="{{ route('selections.collection', [request('course'), request('intake')]) }}"  class="btn btn-warning ml-2"> View  Collection</a>
                @endcan
                @endif
            </div>

            <div>
                <table class="table table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th width="35px" class="border-right">SN</th>
                        <th style="width: 15%">Full name</th>
                        {{-- <th>Phone</th> --}}
                        {{-- <th>Email</th> --}}
                        {{-- <th style="width: 20%">School name</th> --}}
                        <th>Index Number</th>
                        {{-- <th>Course</th> --}}
                        <th>Points</th>
                        <th style="width: 50%">Result</th>
                        <th>Apply Date</th>
                        @can('collect selected')
                        <th class="text-primary font-weight-bold"><a href="#" class="nav-item" id="selectAll">Select All</a></th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    <form id="selectionForm" method="post" action="{{ route('selections.collect') }}">
                        <input type="hidden" name="course" value="{{ request('course') }}">
                        <input type="hidden" name="intake" value="{{ request('intake') }}">
                        @csrf
                        @forelse($selections as $item)
                            <tr>
                                <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                                <td><a href="{{ route('admission.show', $item->admission->id)  }}">
                                        {{ $item->admission->first_name ." ". $item->admission->middle_name ." ". $item->admission->last_name}}</a>
                                </td>
                                {{-- <td>{{ $item->admission->phone }}</td> --}}
                                {{-- <td>{{ $item->admission->email }}</td> --}}
                                {{-- <td>{{ $item->admission->school_name }}</td> --}}
                                <td>{{ $item->admission->index_number }}</td>
                                {{-- <td>{{ $item->course->name}}</td> --}}
                                <td>{{ $item->admission->points }}</td>
                                <td>
                                    @if(isset($item->admission->results[0]->result_payload))
                                        @php
                                            $resultPayload = json_decode($item->admission->results[0]->result_payload);
                                            $displayedCount = 0;
                                        @endphp
                                        @if(isset($resultPayload->subjects))
                                            @foreach($resultPayload->subjects as $subject)
                                                @if(in_array($subject->grade, ['D', 'C', 'B', 'A']) and $displayedCount < 6)
                                                    @if ($subject->subject_name == 'BIOLOGY')
                                                        BIO
                                                    @elseif($subject->subject_name == 'CHEMISTRY')
                                                        CHEM 
                                                    @elseif($subject->subject_name == 'PHYSICS')
                                                        PHY 
                                                    @elseif($subject->subject_name == 'ENGLISH LANGUAGE')
                                                        ENG 
                                                    @elseif($subject->subject_name == 'GEOGRAPHY')
                                                        GEO 
                                                    @elseif($subject->subject_name == 'BASIC MATHEMATICS')
                                                        MATH 
                                                    @elseif($subject->subject_name != 'ELIMU YA DINI YA KIISLAMU')
                                                        {{ $subject->subject_name }} 
                                                    @endif
                                                     : <strong>{{ $subject->grade }}</strong>
                                                    @php
                                                        $displayedCount++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </td>
                                <td>{{ date_format($item->admission->created_at, "d-m-Y") }}</td>
                                @can('collect selected')
                                <td>
                                    <div class="float-right">
                                        <input type="checkbox" name="selected[]" value="{{ $item->admission->id}}"
                                            class="picker form-check-input" id="{{ $item->admission->id}}">
                                        <label class="form-check-label text-success font-weight-bold"
                                            for="{{ $item->admission->id}}">Select</label>
                                    </div>
                                </td>
                                @endcan
                            </tr>
                        @empty
                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let state = false;
        $('#selectAll').on('click', function () {
            state = !state
            $('.picker').prop('checked', state)
        })

        $('#collect').on('click', function () {
            alert('we got it')
            $('#selectionForm').submit()
        })

        $(document).ready(function() {
            $('.select-2').select2();
        });
    </script>
@endpush
