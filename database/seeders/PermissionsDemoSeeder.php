<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'module.users']);
        Permission::create(['name' => 'module.purchase']);
        Permission::create(['name' => 'module.products']);
        Permission::create(['name' => 'module.reports']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'admin']);
        $role1->givePermissionTo('module.users');
        $role1->givePermissionTo('module.purchase');
        $role1->givePermissionTo('module.products');
        $role1->givePermissionTo('module.reports');

        $role2 = Role::create(['name' => 'cashier']);
        $role2->givePermissionTo('module.products');

        $role4 = Role::create(['name' => 'account']);
        $role4->givePermissionTo('module.purchase');
        $role4->givePermissionTo('module.products');


        $role3 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user1 = \App\Models\User::factory()->create([
            'name' => 'Example Admin',
            'email' => 'admin@example.com',
        ]);
        $user1->assignRole($role1);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'Example Cashier',
            'email' => 'cashier@example.com',
        ]);
        $user2->assignRole($role2);

        $user3 = \App\Models\User::factory()->create([
            'name' => 'Example Account',
            'email' => 'account@example.com',
        ]);
        $user3->assignRole($role4);

        $user4 = \App\Models\User::factory()->create([
            'name' => 'Example Super-Admin User',
            'email' => 'superadmin@example.com',
        ]);
        $user4->assignRole($role3);
    }
}
