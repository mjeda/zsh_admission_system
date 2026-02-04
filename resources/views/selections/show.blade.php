@extends('layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Collection View</h1>
    </div>
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between mb-2">
                    <a href="{{ route('selections') }}"  class="btn btn-success ml-2"> Back to Selection</a>
                @can('submit to NACTE')
                <form class="form-inline" action="{{ route('selections.submission', ['course' => $course, 'intake' => $intake]) }}">
                    <label for="receipt">Receipt Number:</label>
                    <input type="text" name="receipt" class="form-control ml-2" id="receipt" required>
                    <button type="submit" class="btn btn-primary ml-2">Submit to NACTE</button>
                </form>
                @endcan
                @can('view submission')
                <a href="{{ route('selections.submission_index', [$course, $intake]) }}" class="btn btn-success ml-2">View Submission</a>
                @endcan
                @can('view status')
                <a href="{{ route('selections.submission_approval_index', [$course, $intake]) }}" class="btn btn-success ml-2">View Status</a>
                @endcan
            </div>

            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Full name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>School name</th>
                    <th>Index Number</th>
                    <th>Result</th>
                    {{-- <th style="width: 30%">Result</th> --}}
                    <th>Status</th>
                    @can('remove collection selected')
                    <th class="font-weight-bold">Action</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                    @forelse($collections as $item)
                        <tr>
                            <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                            <td><a href="{{ route('admission.show', $item->admission->id)  }}">
                                    {{ $item->admission->first_name ." ". $item->admission->middle_name ." ". $item->admission->last_name}}</a>
                            </td>
                            <td>{{ $item->admission->email }}</td>
                            <td>{{ $item->admission->phone }}</td>
                            <td>{{ $item->admission->school_name }}</td>
                            <td>{{ $item->admission->index_number }}</td>
                            <td>{{ $item->course->name }}</td>
                            {{-- <td>
                                @if(isset($item->admission->results[0]->result_payload))
                                    @php
                                        $resultPayload = json_decode($item->admission->results[0]->result_payload);
                                        $displayedCount = 0;
                                    @endphp
                                    @if(isset($resultPayload->subjects))
                                        @foreach($resultPayload->subjects as $subject)
                                            @if($displayedCount < 5) 
                                                @if(in_array($subject->subject_name, ['BIOLOGY', 'CHEMISTRY', 'PHYSICS', 'ENGLISH LANGUAGE', 'BASIC MATHEMATICS']) && in_array($subject->grade, ['D', 'C', 'B', 'A']))
                                                    @if ($subject->subject_name == 'BIOLOGY')
                                                        BIO
                                                    @elseif($subject->subject_name == 'CHEMISTRY')
                                                        CHEM 
                                                    @elseif($subject->subject_name == 'PHYSICS')
                                                        PHY 
                                                    @elseif($subject->subject_name == 'ENGLISH LANGUAGE')
                                                        ENG 
                                                    @elseif($subject->subject_name == 'BASIC MATHEMATICS')
                                                        MATH  
                                                    @endif
                                                    : <strong>{{ $subject->grade }}</strong>
                                                    @php
                                                        $displayedCount++;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </td> --}}
                            <td>{{ $item->status }}</td>
                            @can('remove collection selected')
                            <td>
                                @if($item->status == 'SELECTED')
                                <div class="float-right">
                                  <a href="{{ route('selections.delete', $item->id) }}" class="nav-item text-danger" onclick="return confirm('Are you sure ?')">Remove</a>
                                </div>
                                @else
                                    -
                                @endif
                            </td>
                            @endcan
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