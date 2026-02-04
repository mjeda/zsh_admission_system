<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        $this->command->info('truncating permissions');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();

        $permission = array(
            array('name' => 'system audit', 'group' => 'admin', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # admission
            array('name' => 'view admission', 'group' => 'admission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'edit admission', 'group' => 'admission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'print admission', 'group' => 'admission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'change admission course', 'group' => 'admission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'run NECTA API', 'group' => 'admission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # user
            array('name' => 'view user', 'group' => 'user', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'add user', 'group' => 'user', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'delete user', 'group' => 'user', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'edit user', 'group' => 'user', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),

            # role and permission
            array('name' => 'view permission', 'group' => 'user permission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'add permission', 'group' => 'user permission', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # intake
            array('name' => 'view intake', 'group' => 'intake', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'add intake', 'group' => 'intake', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'delete intake', 'group' => 'intake', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'edit intake', 'group' => 'intake', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'change intake status', 'group' => 'intake', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # course
            array('name' => 'view course', 'group' => 'course', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'add course', 'group' => 'course', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'delete course', 'group' => 'course', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'edit course', 'group' => 'course', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'change course status', 'group' => 'course', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # criteria
            array('name' => 'view criteria', 'group' => 'criteria', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'add criteria', 'group' => 'criteria', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'delete criteria', 'group' => 'criteria', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'edit criteria', 'group' => 'criteria', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # payment and control number
            array('name' => 'view payment and control number', 'group' => 'payment and control number', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'pay control number', 'group' => 'payment and control number', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            # selection
            array('name' => 'view selection', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'view collection', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'view submission', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'view status', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'remove collection selected', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'collect selected', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'submit to NACTE', 'group' => 'selection', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),

            # report
            array('name' => 'view payment report', 'group' => 'report', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'download payment report', 'group' => 'report', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'view admission report', 'group' => 'report', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'download admission report', 'group' => 'report', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'search result', 'group' => 'report', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
            array('name' => 'download result searched', 'group' => 'report', 'guard_name' => 'web', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()),
        );
        DB::table('permissions')->insert($permission);
    }
}