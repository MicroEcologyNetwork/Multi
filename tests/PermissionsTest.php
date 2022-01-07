<?php

use MicroEcology\Multi\Auth\Database\Multiistrator;
use MicroEcology\Multi\Auth\Database\Permission;
use MicroEcology\Multi\Auth\Database\Role;

class PermissionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Multiistrator::first(), 'multi');
    }

    public function testPermissionsIndex()
    {
        $this->assertTrue(Multiistrator::first()->isMultiistrator());

        $this->visit('multi/auth/permissions')
            ->see('Permissions');
    }

    public function testAddAndDeletePermissions()
    {
        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => ['GET']])
            ->seePageIs('multi/auth/permissions')
            ->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete', 'name' => 'Can delete', 'http_path' => 'users/1', 'http_method' => ['DELETE']])
            ->seePageIs('multi/auth/permissions')
            ->seeInDatabase(config('multi.database.permissions_table'), ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => 'GET'])
            ->seeInDatabase(config('multi.database.permissions_table'), ['slug' => 'can-delete', 'name' => 'Can delete', 'http_path' => 'users/1', 'http_method' => 'DELETE'])
            ->assertEquals(7, Permission::count());

        $this->assertTrue(Multiistrator::first()->can('can-edit'));
        $this->assertTrue(Multiistrator::first()->can('can-delete'));

        $this->delete('multi/auth/permissions/6')
            ->assertEquals(6, Permission::count());

        $this->delete('multi/auth/permissions/7')
            ->assertEquals(5, Permission::count());
    }

    public function testAddPermissionToRole()
    {
        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create', 'http_path' => 'users/create', 'http_method' => ['GET']])
            ->seePageIs('multi/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('multi/auth/roles/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1]])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.role_permissions_table'), ['role_id' => 1, 'permission_id' => 1]);
    }

    public function testAddPermissionToUser()
    {
        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create', 'http_path' => 'users/create', 'http_method' => ['GET']])
            ->seePageIs('multi/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('multi/auth/users/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1], 'roles' => [1]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.user_permissions_table'), ['user_id' => 1, 'permission_id' => 1])
            ->seeInDatabase(config('multi.database.role_users_table'), ['user_id' => 1, 'role_id' => 1]);
    }

    public function testAddUserAndAssignPermission()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('multi/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.users_table'), ['username' => 'Test']);

        $this->assertFalse(Multiistrator::find(2)->isMultiistrator());

        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-update', 'name' => 'Can Update', 'http_path' => 'users/*/edit', 'http_method' => ['GET']])
            ->seePageIs('multi/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove', 'http_path' => 'users/*', 'http_method' => ['DELETE']])
            ->seePageIs('multi/auth/permissions');

        $this->assertEquals(7, Permission::count());

        $this->visit('multi/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [6]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 6]);

        $this->assertTrue(Multiistrator::find(2)->can('can-update'));
        $this->assertTrue(Multiistrator::find(2)->cannot('can-remove'));

        $this->visit('multi/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [7]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 7]);

        $this->assertTrue(Multiistrator::find(2)->can('can-remove'));

        $this->visit('multi/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => []])
            ->seePageIs('multi/auth/users')
            ->missingFromDatabase(config('multi.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 6])
            ->missingFromDatabase(config('multi.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 7]);

        $this->assertTrue(Multiistrator::find(2)->cannot('can-update'));
        $this->assertTrue(Multiistrator::find(2)->cannot('can-remove'));
    }

    public function testPermissionThroughRole()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // 1.add a user
        $this->visit('multi/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.users_table'), ['username' => 'Test']);

        $this->assertFalse(Multiistrator::find(2)->isMultiistrator());

        // 2.add a role
        $this->visit('multi/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->assertFalse(Multiistrator::find(2)->isRole('developer'));

        // 3.assign role to user
        $this->visit('multi/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [2]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.role_users_table'), ['user_id' => 2, 'role_id' => 2]);

        $this->assertTrue(Multiistrator::find(2)->isRole('developer'));

        //  4.add a permission
        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove', 'http_path' => 'users/*', 'http_method' => ['DELETE']])
            ->seePageIs('multi/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->assertTrue(Multiistrator::find(2)->cannot('can-remove'));

        // 5.assign permission to role
        $this->visit('multi/auth/roles/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [6]])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.role_permissions_table'), ['role_id' => 2, 'permission_id' => 6]);

        $this->assertTrue(Multiistrator::find(2)->can('can-remove'));
    }

    public function testEditPermission()
    {
        $this->visit('multi/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => ['GET']])
            ->seePageIs('multi/auth/permissions')
            ->seeInDatabase(config('multi.database.permissions_table'), ['slug' => 'can-edit'])
            ->seeInDatabase(config('multi.database.permissions_table'), ['name' => 'Can edit'])
            ->assertEquals(6, Permission::count());

        $this->visit('multi/auth/permissions/1/edit')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete'])
            ->seePageIs('multi/auth/permissions')
            ->seeInDatabase(config('multi.database.permissions_table'), ['slug' => 'can-delete'])
            ->assertEquals(6, Permission::count());
    }
}
