<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @see https://spatie.be/docs/laravel-permission/v5/advanced-usage/seeding
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->create_permission();

        $this->set_role_admin();

        $this->set_role_coach();

        // create roles and assign created permissions

        // this can be done as separate statements
        //$role = Role::create(['name' => 'writer']);
        //$role->givePermissionTo('edit articles');

        // or may be done by chaining
        // $role = Role::create(['name' => 'moderator'])
        //     ->givePermissionTo(['publish articles', 'unpublish articles']);

        // $role = Role::create(['name' => 'super-admin']);
        // $role->givePermissionTo(Permission::all());
    }

    private function create_permission()
    {
        // create permissions
        Permission::create(['name' => 'session.create']);
        Permission::create(['name' => 'session.edit']);
        Permission::create(['name' => 'session.delete']);

        Permission::create(['name' => 'session.step_create']);
        Permission::create(['name' => 'session.step_edit']);
        Permission::create(['name' => 'session.step_status']);
    }

    private function set_role_admin()
    {
        $role = Role::create(['name' => 'admin'])
            ->givePermissionTo([
                'session.create', 'session.edit', 'session.delete', 'session.step_create'
            ]);
    }

    private function set_role_coach()
    {
        $role = Role::create(['name' => 'coach'])
            ->givePermissionTo(['session.step_status']);
    }

    private function set_role_client()
    {
        $role = Role::create(['name' => 'client'])
            ->givePermissionTo([]);
    }
}
