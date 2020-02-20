<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // users permissions
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'read users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        // create permissions
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'read permissions']);
        Permission::create(['name' => 'update permissions']);
        Permission::create(['name' => 'delete permissions']);
        Permission::create(['name' => 'assign permissions']);
        Permission::create(['name' => 'read role-permissions']);
        Permission::create(['name' => 'read user-permissions']);

        // roles permissions
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'read roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);
        Permission::create(['name' => 'assign roles']);

        // currencies permissions
        Permission::create(['name' => 'create currencies']);
        Permission::create(['name' => 'read currencies']);
        Permission::create(['name' => 'update currencies']);
        Permission::create(['name' => 'delete currencies']);

        // ips permissions
        Permission::create(['name' => 'create ips']);
        Permission::create(['name' => 'read ips']);
        Permission::create(['name' => 'update ips']);
        Permission::create(['name' => 'delete ips']);

        // tariff permissions
        Permission::create(['name' => 'create tariff']);
        Permission::create(['name' => 'read tariff']);
        Permission::create(['name' => 'update tariff']);
        Permission::create(['name' => 'delete tariff']);

        // rates permissions
        Permission::create(['name' => 'create rates']);
        Permission::create(['name' => 'read rates']);
        Permission::create(['name' => 'update rates']);
        Permission::create(['name' => 'delete rates']);
        Permission::create(['name' => 'export rates']);
        Permission::create(['name' => 'download rates']);

        // bill-simulation permissions
        Permission::create(['name' => 'simulate bill']);

        // cdr-logs permissions
        Permission::create(['name' => 'read cdr-logs']);
        Permission::create(['name' => 'parse cdr-logs']);

        // calls-summary permissions
        Permission::create(['name' => 'read calls-summary']);
        Permission::create(['name' => 'export calls-summary']);

        // access-logs permissions
        Permission::create(['name' => 'read access-logs']);

        // company-settings permissions
        Permission::create(['name' => 'read company-settings']);
        Permission::create(['name' => 'update company-settings']);

        // privacy-policy permissions
        Permission::create(['name' => 'read privacy-policy']);
        Permission::create(['name' => 'update privacy-policy']);

        // gateways permissions
        Permission::create(['name' => 'create gateways']);
        Permission::create(['name' => 'read gateways']);
        Permission::create(['name' => 'update gateways']);
        Permission::create(['name' => 'delete gateways']);
        Permission::create(['name' => 'read gateway-payments']);
        Permission::create(['name' => 'create gateway-payments']);

        // clients permissions
        Permission::create(['name' => 'create clients']);
        Permission::create(['name' => 'read clients']);
        Permission::create(['name' => 'update clients']);
        Permission::create(['name' => 'delete clients']);
        Permission::create(['name' => 'read client-payments']);
        Permission::create(['name' => 'create client-payments']);

        // General action permisssions
        Permission::create(['name' => 'read payments-types']);
        Permission::create(['name' => 'read countries']);

        Permission::create(['name' => 'read calls-summary client']);
        Permission::create(['name' => 'export calls-summary client']);

        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'writer']);
        // $role->givePermissionTo('edit articles');

        // or may be done by chaining
        // $role = Role::create(['name' => 'moderator'])
        //     ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());

    }
}
