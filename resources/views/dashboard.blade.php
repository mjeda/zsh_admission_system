@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <form method="GET" class="d-flex">
            @csrf
            <div class="input-group">
                <select name="intake" class="form-select">
                    @foreach($intakes as $intake)
                        <option value="{{$intake->id}}" @if(request('intake') == $intake->id) selected @endif>{{$intake->type. ' - '. $intake->title. " round ". $intake->round .", ". $intake->year}}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter"> Filter</i>
                </button>
            </div>
        </form>
    </div>
    <div class="mt-n22 px-6">
        <!-- row for admission card -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4 col-12 mt-6">
                        <!-- card -->
                        <div class="card rounded-3">
                            <!-- card body -->
                            <div class="card-body bg-light">
                                <!-- heading -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0">
                                            <form action="{{route('admission.index')}}">
                                                <input hidden="" type="text" name="status" class="form-control" value="">
                                                <input hidden="" type="text" name="intake" class="form-control" value="{{ $last_intake?->id }}">
                                                <button type="submit" value="Search" class="btn btn-link text-gray-300 text-dark font-weight-bold" style="font-size: 18px; text-decoration: none">
                                                    Total Applications
                                                </button>
                                            </form>
                                        </h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-primary text-primary rounded-1">
                                        <i class="fa fa-users fs-4"></i>
                                    </div>
                                </div>
                                <!-- project number -->
                                <div class="px-2">
                                    <h1 class="fw-bold">{{$total_admission }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mt-6">
                        <!-- card -->
                        <div class="card rounded-3">
                            <!-- card body -->
                            <div class="card-body text-info bg-light">
                                <!-- heading -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0">
                                            <form action="{{route('admission.index')}}">
                                                <input hidden="" type="text" name="status" class="form-control" value="CREATED">
                                                <input hidden="" type="text" name="intake" class="form-control" value="{{ $last_intake?->id }}">
                                                <button type="submit" value="Search" class="btn btn-link text-gray-300 text-info font-weight-bold" style="font-size: 18px; text-decoration: none">
                                                    In-progress Applications
                                                </button>
                                            </form>
                                        </h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-primary text-primary rounded-1">
                                        <i class="fa fa-user fs-4"></i>
                                    </div>
                                </div>
                                <!-- project number -->
                                <div class="px-2">
                                    <h1 class="fw-bold">{{ $created_admission }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mt-6">
                        <!-- card -->
                        <div class="card rounded-3">
                            <!-- card body -->
                            <div class="card-body bg-light text-success">
                                <!-- heading -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0">
                                            <form action="{{route('admission.index')}}">
                                                <input hidden="" type="text" name="status" class="form-control" value="SUBMITTED">
                                                <input hidden="" type="text" name="intake" class="form-control" value="{{ $last_intake?->id }}">
                                                <button type="submit" value="Search" class="btn btn-link text-gray-300 text-success font-weight-bold" style="font-size: 18px; text-decoration: none">
                                                    Completed Applications
                                                </button>
                                            </form>
                                        </h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-primary text-primary rounded-1">
                                        <i class="fa fa-sticky-note fs-4"></i>
                                    </div>
                                </div>
                                <!-- project number -->
                                <div class="px-2">
                                    <h1 class="fw-bold">{{ $submitted_admission }}</h1>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- row Collection of admissions by course  -->
        <div class="row mt-2">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <label class="label label-success">Collection of applications by course </label>
                        <div>
                            <canvas id="admission-chart" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pie chart -->
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <label class="label label-success">Selected student by course</label>
                        <div>
                            <canvas id="myChart" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row for table display admission for today  -->
        <div class="row mt-2">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <label class="label label-success">Today's <b>Applicant Admissions</b></label>
                        <div class="table-responsive">
                            <table class="table table-striped border-left border-right border-bottom">
                                <thead>
                                <tr>
                                    <th width="35px" class="border-right">SN</th>
                                    <th>Full name</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>School name</th>
                                    <th>Index Number</th>
                                    <th>Created at</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($today_admissions as $item)
                                    <tr>
                                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->first_name ." ". $item->middle_name ." ". $item->last_name}}</td>
                                        <td>{{ $item->gender }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->school_name }}</td>
                                        <td>{{ $item->index_number }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <div class="float-right">
                                                <a href="{{ route('admission.show', $item)  }}" class="btn btn-info btn-sm">Detail</a>
                                            </div>
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
                    <div class="card-footer">
                        @if($today_admissions)
                            {{ $today_admissions->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const course = document.getElementById('admission-chart');

        new Chart(course, {
            type: 'bar',
            data: {
                labels: {{ Js::from($course_name) }},
                datasets: [
                    {
                        label: '# of admissions',
                        data: {{ Js::from($course_value) }},
                        borderWidth: 1
                    },
                    {
                        label: '# of first choice',
                        data: {{ Js::from($first_choice) }},
                        borderWidth: 1
                    },
                    {
                        label: '# of seecond choice',
                        data: {{ Js::from($second_choice) }},
                        borderWidth: 1
                    },
                    {
                        label: '# of third choice',
                        data: {{ Js::from($third_choice) }},
                        borderWidth: 1
                    },
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {{ Js::from($selected_course_name) }},
                datasets: [{
                    label: ' # Selected students ',
                    data: {{ Js::from($selected_course_value) }},
                    hoverOffset: 4,
                }]
            },
        });
    </script>
@endpush
