@extends('layouts.app')

@section('content')
    <div class="float-right mt-2">
        <a href="{{ route('admission.reset.password', $admission->user->id) }}" class="btn btn-primary" onclick="return confirm('Do you want to reset password?')">
            Reset password
        </a>
        <button type="button" class="btn btn-success text-black" data-bs-toggle="modal" data-bs-target="#updateModal">
            Update info ...
        </button>
        <a href="#" class="btn btn-secondary" onclick="PrintElem('#printer')"><i class="fa fa-print"></i> Print</a>
    </div>
    <div id="printer">
        <div
            class="align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h2 class="h4">Application of
                <i class="font-weight-bold">{{ $admission->first_name ." ". $admission->middle_name ." ". $admission->last_name}}</i>
            </h2>
            <h3> {{ $admission->index_number .' '. $admission->index_number_2 .' '. $admission->index_number_3 }} ({{ $admission->intake->title .' '. $admission->intake->year }} Intake)</h3>
        </div>
        <!-- Personal info --->
        <fieldset>
            <legend class="text-info">Personal Information</legend>
            <div class="table-responsive">
                <table class="table table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th>School name</th>
                        <th>Index Number</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Region</th>
                        <th>District</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $admission->school_name}}</td>
                        <td>{{ $admission->index_number }}</td>
                        <td>{{ $admission->gender }}</td>
                        <td>{{ $admission->dob }}</td>
                        <td>{{ $admission->email }}</td>
                        <td>{{ $admission->phone }}</td>
                        <td>{{ $admission->address }}</td>
                        <td>{{ $admission->region }}</td>
                        <td>{{ $admission->district }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <!-- Next of kin info --->
        <fieldset>
            <legend class="text-info">Next of kin Information</legend>
            <div class="table-responsive">
                <table class="table table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th>Full name</th>
                        <th>Relation</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>District</th>
                        <th>Region</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($admission->next_of_kin_phone != null)
                        <tr>
                            <td>{{ $admission->next_of_kin_name}}</td>
                            <td>{{ $admission->next_of_kin_relation }}</td>
                            <td>{{ $admission->next_of_kin_phone }}</td>
                            <td>{{ $admission->next_of_kin_email }}</td>
                            <td>{{ $admission->next_of_kin_address }}</td>
                            <td>{{ $admission->next_of_kin_district }}</td>
                            <td>{{ $admission->next_of_kin_region }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="10" class="text-center text-danger">
                                Information of next of kin not filled
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </fieldset>
        <!-- Payment info --->
        <fieldset>
            <legend class="text-info">Payment Information</legend>
            <div class="table-responsive">
                <table class="table  table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th>Control number</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($admission->controlNumber != null)
                        @if($admission->controlNumber->payment != null)
                            <tr>
                                <td>{{ $admission->controlNumber->control_number }}</td>
                                <td>{{ $admission->controlNumber->payment->amount }}</td>
                                <td>{{ $admission->controlNumber->payment->payment_method }}</td>
                                <td>{{ $admission->controlNumber->payment->payment_date }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="10" class="text-center text-danger">
                                    The control number <b>{{ $admission->controlNumber->control_number }}</b> not paid
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="10" class="text-center text-danger">
                                Control number not generated because admission not completed
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </fieldset>
        <!-- Selected Course info --->
        <fieldset>
            <legend class="text-info">Course Selected</legend>
            <div class="table-responsive">
                <table class="table  table-striped border-left border-right border-bottom">
                    <thead>
                    <tr>
                        <th>Course code</th>
                        <th>Course name</th>
                        <th>Priority</th>
                        <th>Selected at</th>
                        <th class="hide">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($admission->selectedCourse as $selected)
                            @if($selected->course != null)
                                <tr>
                                    <td>{{ $selected->course->tag }}</td>
                                    <td>{{ $selected->course->name }}</td>
                                    <td>{{ $selected->priority }}</td>
                                    <td>{{ $selected->created_at }}</td>
                                    <td class="hide">
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#changeModal" data-id="{{ $selected->id }}">
                                            Change
                                        </button>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="10" class="text-center text-danger">
                                        Not selected any course
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-danger">
                                    @if($admission->controlNumber != null)
                                        @if($admission->controlNumber->payment != null)
                                        <a href="{{ route('admission.update', $admission->id) }}" class="btn btn-warning">Reload Results</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </fieldset>
        <!-- Result info --->
        <fieldset>
            <legend class="text-info">Results</legend>
            @forelse($admission->results as $result)
                <p>Index Number: {{json_decode($result->result_payload)->particulars->index_number}}</p>
                <div class="table-responsive">
                    <table class="table  table-striped border-left border-right border-bottom">
                        <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(json_decode($result->result_payload)->subjects ?? [] as $payload)
                            <tr>
                                <td>{{$payload->subject_code}}</td>
                                <td>{{$payload->subject_name}}</td>
                                <td>{{$payload->grade}}</td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            @empty
            @endforelse
        </fieldset>
    </div>
    <!-- Change Course Modal -->
    <div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Course</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="changeCourseForm">
                        @csrf
                        <div class="form-group">
                            <label for="course">Select Course</label>
                            <select id="course" name="course" class="form-control">
                                <option value="">--Select--</option>
                                @forelse($courses as $course)
                                    <option value="{{$course->id}}"> {{ $course->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('course')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Update Information Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Student Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('admission.update.info', $admission->id)}}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" value="{{ $admission->email }}" class="form-control" name="email" id="email">
                            @error('email')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone number</label>
                            <input type="text" value="{{ $admission->phone }}" id="phone_number" name="phone" class="form-control">
                            @error('phone')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let changeCourseBtns = document.querySelectorAll(".btn[data-bs-target='#changeModal']");

        changeCourseBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                let courseId = this.getAttribute("data-id");
                let form = document.getElementById("changeCourseForm");
                
                // Update the form action correctly
                form.action = "{{ url('admission') }}/change/" + courseId ;
            });
        });
    });
</script>
@endpush
