<?php

use MicroEcology\Multi\Auth\Database\Multiistrator;
use MicroEcology\Multi\Auth\Database\Role;

class RolesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Multiistrator::first(), 'multi');
    }

    public function testRolesIndex()
    {
        $this->visit('multi/auth/roles')
            ->see('Roles')
            ->see('multiistrator');
    }

    public function testAddRole()
    {
        $this->visit('multi/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());
    }

    public function testAddRoleToUser()
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

        $this->assertEquals(1, Role::count());

        $this->visit('multi/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->assertFalse(Multiistrator::find(2)->isRole('developer'));

        $this->visit('multi/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [2]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.role_users_table'), ['user_id' => 2, 'role_id' => 2]);

        $this->assertTrue(Multiistrator::find(2)->isRole('developer'));

        $this->assertFalse(Multiistrator::find(2)->inRoles(['editor', 'operator']));
        $this->assertTrue(Multiistrator::find(2)->inRoles(['developer', 'operator', 'editor']));
    }

    public function testDeleteRole()
    {
        $this->assertEquals(1, Role::count());

        $this->visit('multi/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->delete('multi/auth/roles/2')
            ->assertEquals(1, Role::count());

        $this->delete('multi/auth/roles/1')
            ->assertEquals(0, Role::count());
    }

    public function testEditRole()
    {
        $this->visit('multi/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->visit('multi/auth/roles/2/edit')
            ->see('Roles')
            ->submitForm('Submit', ['name' => 'blablabla'])
            ->seePageIs('multi/auth/roles')
            ->seeInDatabase(config('multi.database.roles_table'), ['name' => 'blablabla'])
            ->assertEquals(2, Role::count());
    }
}
