@extends('layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">View feedback error</h1>
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
                </form>
            </div>
        </div>

            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Full name</th>
                    <th>Index number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Remark</th>
                    <th width="80"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($feedback_errors->params??[] as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $item->firstname }} {{ $item->secondname }} {{ $item->surname }}</td>
                        <td>{{ $item->user_id }}</td>
                        <td>{{ $item->mobile_number }}</td>
                        <td>{{ $item->email_address }}</td>
                        <td>{{ $item->remarks }}</td>
                        <td>
                            <a href="{{ route('nacte.fix-error', $loop->index) }}" class="btn btn-success float-end">
                                fix it
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
