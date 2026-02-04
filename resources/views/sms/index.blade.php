@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage SMS</h1>
    </div>
    <!-- Form --->
    @canany(['add intake', 'edit intake'])
        @livewire('sms-component')
    @endcanany
@endsection
