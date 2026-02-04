@extends('layouts.portal')

@section('content')
    <div class="card shadow-sm mb-4">
        {{-- Current Intake --}}
        @if($active_intake)
            <div class="card-body">
                <h4 class="mb-4">📄 My applications</h4>
                <h5 class="fw-bold">
                    Application for {{ $currentApplication?->intake?->title }} Intake: Round {{ $currentApplication?->intake?->round }}
                </h5>

                @if($currentApplication)
                    @if($currentApplication->status === 'SELECTED')
                        <p class="text-success mb-1">
                            🎉 Congratulations! You have been SELECTED for the following course:
                        </p>
                        <p class="fw-bold text-capitalize mb-0">
                            {{ $currentApplication->selection->course->name }}
                        </p>

                        <div class="alert alert-info">
                            @if($currentApplication->registrationControlNumber)
                                📝 Tafadhali fanya malipo ya usaili kwa kutumia namba ya udhibiti (Control Number)
                                ** <strong>{{ $currentApplication->registrationControlNumber->control_number }}</strong> **
                                <br><br>
                                Malipo yanaweza kufanyika kwa njia zifuatazo:<br>
                                1. Kupitia benki ya PBZ moja kwa moja au wakala wake.<br>
                                2. Kwa simu, kutumia njia ya Lipa kwa Mix By Yas (ikiwa inapatikana).
                                <br><br>
                                🕒 Mwisho wa kufanya malipo: <strong>{{ $currentApplication->registrationControlNumber->expire_date->format('d M Y') }}</strong>
                            @else
                                Tafadhali subiri namba ya udhibiti (Control Number) kwa ajili ya kufanya malipo ya usaili.
                            @endif
                        </div>

                    @else
                        <p class="text-primary mb-1">✅ You have already submitted your application.</p>
                        <p class="mb-0"><strong>Status:</strong> {{ $currentApplication->status }}</p>
                    @endif
                @else
                    <p class="text-muted">You have not completed an application for this intake.</p>
                    <a href="{{ route('portal-admission') }}" class="btn btn-primary mt-2">
                        ➕ New Application
                    </a>
                @endif
            </div>
        @endif
    </div>

    {{-- Previous Completed Application --}}
    @if($previousApplications)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">📌 Previous Application</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Intake</th>
                                <th>Step</th>
                                <th>Created Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($previousApplications as $index => $previousApplication)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $previousApplication->intake?->title ?? '-' }} - Round {{ $previousApplication->intake?->round ?? '-' }}</td>
                                    <td>
                                        @if($previousApplication->step == 4 && $previousApplication->status != 'CREATED')
                                            4 - finished
                                        @elseif($previousApplication->step == 4 && $previousApplication->status == 'CREATED')
                                            4 - Not Completed
                                        @else
                                            Step - {{ $previousApplication->step }}
                                        @endif
                                    </td>
                                    <td>{{ $previousApplication->created_at->format('d F, Y') }}</td>
                                    <td class="fw-bold {{ $previousApplication->status === 'SELECTED' ? 'text-success' : 'text-info' }}">
                                        {{ $previousApplication->status }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif
@endsection
