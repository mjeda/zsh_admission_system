<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            "name" => "admin",
            "email" => "admin.zsh@admin.com",
            "password" => Hash::make("zsh@23"),
            "type" => "Admin",
        ]);

        $admin->givePermissionTo(Permission::all());
    }
}
