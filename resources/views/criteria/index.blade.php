@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Course Criteria</h1>
    </div>
    <!-- Form --->
    @canany(['add criteria', 'edit criteria'])
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-muted font-weight-bold">@if($course_criteria->exists) Edit @else Add @endif Criteria</h5>
            </div>
            <div class="row">
                <div class="col">
                    <form action="@if($course_criteria->exists) {{ route('criteria.update', $course_criteria) }} @else {{ route('criteria.store', $course) }} @endif" method="POST" class="pb-3">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-4 pt-3 pr-3 pl-3">
                                <label for="title">Subject Name</label>
                                <input type="text" value="{{ $course_criteria->title }}" class="form-control  @error('title') is-invalid @enderror" name="title" id="title" placeholder="eg. KISWAHILI">
                                @error("title")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="value">Minimum Grade</label>
                                <input type="text" value="{{ $course_criteria->value }}" class="form-control @error('value') is-invalid @enderror" name="value" id="value" placeholder="eg. D">
                                @error("value")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-2 pt-3 pr-3 pl-3">
                                <label for="value">Filter</label>
                                <select  class="form-control @error('filter') is-invalid @enderror" name="filter" id="filter">
                                    <option value="">-- Select Filter --</option>
                                    <option value="Include" {{ $course_criteria->filter == 'Include' ? 'selected' : '' }}>Include</option>
                                    <option value="Exclude" {{ $course_criteria->filter == 'Exclude' ? 'selected' : '' }}>Exclude</option>
                                    <option value="Advantage" {{ $course_criteria->filter == 'Advantage' ? 'selected' : '' }}>Advantage</option>
                                    <option value="Alternative" {{ $course_criteria->filter == 'Alternative' ? 'selected' : '' }}>Alternative</option>
                                </select>
                                @error("filter")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4 pt-3 pr-3 pl-3" id="alt-for-section" style="{{ $course_criteria->filter == 'Alternative' ? '' : 'display: none;' }}">
                                <label for="alternative_for">Alternative For (Subject to be compensated)</label>
                                <input type="text" value="{{ $course_criteria->alternative_for }}" class="form-control @error('alternative_for') is-invalid @enderror" name="alternative_for" id="alternative_for" placeholder="eg. PHYSICS">
                                @error("alternative_for")
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row  pt-3 pr-4">
                            <div class="col">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm @if($course_criteria->exists) btn-success @else btn-primary @endif" >
                                        @if($course_criteria->exists) Edit @else Add @endif criteria
                                    </button>
                                    @if($course_criteria->exists)
                                        <a href="{{ route("criteria.index", $course) }}" class="btn btn-light btn-sm">Cancel</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcanany
    <!-- List --->
    <div class="row">
        <div class="col">
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th width="450">Subject Name</th>
                    <th>Minimum Grade</th>
                    <th>Filter</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th width="200"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($criteria as $item)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->value }}</td>
                        <td>{{ $item->filter }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <div class="btn-group float-right">
                                @can('edit criteria')
                                <a href="{{ route('criteria.index', [$course, $item])  }}" class="btn btn-success btn-sm">Edit</a>
                                @endcan
                                @can('delete criteria')
                                <a href="{{ route('criteria.delete', $item) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ! \nDo you want to delete ?')">Remove</a>
                                @endcan
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
            {{ $criteria->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterSelect = document.getElementById('filter');
            const altSection = document.getElementById('alt-for-section');

            function toggleAltSection() {
                if (filterSelect.value === 'Alternative') {
                    altSection.style.display = '';
                } else {
                    altSection.style.display = 'none';
                }
            }

            filterSelect.addEventListener('change', toggleAltSection);
            toggleAltSection(); // initial call
        });
    </script>
@endpush

