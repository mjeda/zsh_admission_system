@extends('layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">View Verified Student</h1>
    </div>
    <div class="row">
        <!-- filter form -->
        <div class="col">
            <div class="d-flex justify-content-start mb-2">
                <form class="form-inline">
                    <select name="intake" class="form-control mr-2 select-2">
                        <option value="">-- Select Intake --</option>
                        @forelse($intakes as $intake)
                            <option value="{{$intake->id}}" @if(request('intake') == $intake->id) selected @endif>{{$intake->title. " round - ". $intake->round .", ". $intake->year}}</option>
                        @empty
                        @endforelse
                    </select>
                    <select name="course" class="form-control mx-2">
                        <option value="">-- Select Course --</option>
                        @forelse($courses as $course)
                            <option value="{{$course->id}}" @if(request('course') == $course->id) selected @endif>{{$course->name}}</option>
                        @empty
                        @endforelse
                    </select>

                    <button type="submit" class="btn btn-primary float-end">
                        Filter
                    </button>

                    @if(count($submitted) > 0)
                        <a href="{{ route('nacte.enrol.all.student', [request()->query('course'), request()->query('intake')]) }}" class="btn btn-info float-end mx-2">
                            enroll all
                        </a>
                    @endif
                </form>
            </div>
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
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($submitted as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $item->username }}}</td>
                        <td>{{ $item->multiple_selection }}</td>
                        <td>{{ $item->academic_year }}</td>
                        <td>{{ $item->intake }}</td>
                        <td>{{ $item->eligibility }}</td>
                        <td>{{ $item->remarks }}</td>
                        <td>{{ $item->verification_status }}</td>
                        <td>
                            <a href="{{ route('nacte.enrol.student', [$item->user_id, request()->query('course'), $item->intake, $item->academic_year]) }}" class="btn btn-primary" onclick="return confirm('Are you sure you want to enrol this student?')">
                                <i class="fa fa-refresh"></i> Enrol
                            </a>
                        </td>
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
