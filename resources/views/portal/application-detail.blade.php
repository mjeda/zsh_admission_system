@extends('layouts.portal')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admission of
            <i class="font-weight-bold">{{ $admission->first_name ." ". $admission->middle_name ." ". $admission->last_name}} - {{ $admission->index_number }}</i>
        </h1>
    </div>
    <!-- Personal info --->
    <fieldset>
        <legend style="color: #e3b04b">Personal Information</legend>
        <div class="table-responsive">
            <table class="table table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th>School name</th>
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
        <legend style="color: #e3b04b">Next of kin Information</legend>
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
        <legend style="color: #e3b04b">Payment Information</legend>
        <div class="table-responsive">
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th>Control Number</th>
                    <th>Receipt</th>
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
                            <td>{{ $admission->controlNumber->payment->receipt }}</td>
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
        <legend style="color: #e3b04b">Course Selected</legend>
        <div class="table-responsive">
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th>Priority</th>
                    <th>Course name</th>
                    <th>Selected at</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($admission->selectedCourse as $selected)
                        @if($selected->course != null)
                        <tr>
                            <td>{{ $selected->priority }}</td>
                            <td>{{ $selected->course->name }}</td>
                            <td>{{ $selected->created_at }}</td>
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
                                Not selected any course
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </fieldset>
@endsection
