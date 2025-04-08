<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'openkab', 'guard_name' => 'web']);
        Role::create(['name' => 'opendk', 'guard_name' => 'web']);

        Permission::create(['name' => 'read-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-user', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-user', 'guard_name' => 'web']);

        Permission::create(['name' => 'read-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'create-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'update-role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete-role', 'guard_name' => 'web']);

        Role::findByName('admin')->givePermissionTo([
            'read-user',
            'create-user',
            'update-user',
            'delete-user',
            'read-role',
            'create-role',
            'update-role',
            'delete-role',
        ]);

        User::create([
            'name' => 'Admin Gabungan',
            'email' => 'admin@admin.com',
            'password' => 'Admin100%',
        ])->assignRole('admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        (new Permission())->whereNotNull('id')->delete();
        (new User())->whereNotNull('id')->delete();
        (new Role())->whereNotNull('id')->delete();

    }
};
