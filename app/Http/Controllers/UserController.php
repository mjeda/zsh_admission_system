<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->whereNotIn("type", ["Candidate", "VTA/VETA"])->paginate(10);
        return view("users.index")->with("users", $users);
    }

    public function create()
    {
        $roles = ['Admin', 'Accountant', 'Staff'];
        return view("users.create", compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "username" => "required|max:150",
            "role" => "required|max:150",
            "email" => "required|email|max:255|unique:users",
            "password" => "required|confirmed",
        ]);

        User::create([
            "name" => $request->input("username"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
            "type" => $request->input("role")
        ]);

        return redirect()->route("user.index")->with("success", "User created successfully");
    }


    public function edit(User $user)
    {
        $roles = ['Admin', 'Accountant', 'Staff'];
        return view("users.edit", compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            "username" => "required|max:150",
            "role" => "required|max:150",
            "email" => "required|email|max:255",
        ]);

        $user->update([
            "name" => $request->input("username"),
            "email" => $request->input("email"),
            "type" => $request->input("role")
        ]);

        return redirect()->route("user.index")->with("success", "User updated successfully");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with("success", "User removed successfully");
    }

    public function getChangePassword(){
        if(auth()->user()->type  == 'admin'){
            return view("users.change-password");
        }
        return view("portal.change-password");
    }

    public function updatePassword(Request $request){
        $request->validate(['current_password' => 'required', 'password' => 'required|min:8|confirmed']);
        if (!Hash::check($request->input("current_password"), auth()->user()->password)) {
            return back()->with('warning', ' You current password not match with password valid. Please try again');
        }

        auth()->user()->update(["password" => Hash::make($request->input("password"))]);
        return back()->with("success", "Password changed successfully");
    }

    public function showPermission(User $user){
        return view('users.permissions', [
            'user' => $user,
            'groups' => Permission::distinct()->get("group"),
            'permissions' => Permission::all(),
        ]);
    }

    public function storePermission(Request $request, User $user){
        $user->syncPermissions($request->input('permissions'));

        return redirect()->route('user.index')->with('success', 'Permission Synced');
    }

    public function resetPassword(User $user)
    {
        $user->password = Hash::make("zsh@staff");
        $user->save();

        return back()->with('success', 'Password Reset Successful. new password id zsh@staff');
    }
}
