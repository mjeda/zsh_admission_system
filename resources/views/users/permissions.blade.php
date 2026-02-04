@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col">
            <h1 class="h3 mb-3">Permission For <strong>{{ $user->name }}</strong></h1>
        </div>
        <div class="col">
            <a href="{{ route('user.index') }}"  class="btn btn-sm btn-primary float-end">
                <span class="fa fa-step-backward me-2"></span> User list
            </a>
        </div>
    </div>

    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <form action="{{ route("store.permission", $user) }}" method="POST">
            @csrf
            <div class="accordion" id="accordionExample">
                @foreach($groups as $group)
                    <div class="card my-2 border-0">
                        <div class="border border-light-primary" id="heading{{ $loop->index }}" style="background: #3c4b64">
                            <h4 class="m-2 text-white-50 text-capitalize">
                                <i class="fa fa-arrow-alt-circle-down"></i> {{ $group->group }}
                            </h4>
                        </div>
                        <div class="row">
                            <div class="card-body row">
                                @foreach($permissions as $permission)
                                    @if( $group->group == $permission->group)
                                        <div class="col-12 col-sm-6 col-md-3 col-lg-3 py-0 ml-3 d-block">
                                            <input type="checkbox"
                                                   @foreach($user->permissions as $userPermission)
                                                   @if ($userPermission->name == $permission->name)
                                                   checked
                                                   @endif
                                                   @endforeach
                                                   id="{{$permission->id}}"
                                                   value="{{$permission->name}}" name="permissions[]">
                                            <label for="{{$permission->id}}"> {{ucfirst($permission->name)}}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-refresh"></i> Sync Permission
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
