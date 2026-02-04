@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage User Accounts</h1>
        @can('add user')
        <div class="float-end">
            <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Add user</a>
        </div>
        @endcan
    </div>
    <div class="row">
        <div class="col">
            <table class="table  table-striped border-left border-right border-bottom">
                <thead>
                <tr>
                    <th width="35px" class="border-right">SN</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td width="35px" class="border-right">{{ $loop->index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->type }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td class="text-nowrap">
                            <div class="btn-group float-right">
                                @can('view permission')
                                    <a href="{{ route('show.permission', $user) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-lock"></i> Permissions
                                    </a>
                                @endcan
                                @can('edit user')
                                    <a href="{{ route('user.reset.password', $user)  }}" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure ! \nDo you want to reset Password ?')">Reset Password</a>
                                    <a href="{{ route('user.edit', $user)  }}" class="btn btn-success btn-sm">Edit</a>
                                @endcan
                                @can('delete user')
                                    <a href="{{ route('user.delete', $user)  }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ! \nDo you want to delete ?')">Remove</a>
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
            {{ $users->links() }}
        </div>
    </div>
@endsection
