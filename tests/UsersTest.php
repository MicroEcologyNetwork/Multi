<?php

use MicroEcology\Multi\Auth\Database\Multiistrator;

class UsersTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = Multiistrator::first();

        $this->be($this->user, 'multi');
    }

    public function testUsersIndexPage()
    {
        $this->visit('multi/auth/users')
            ->see('Multiistrator');
    }

    public function testCreateUser()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // create user
        $this->visit('multi/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.users_table'), ['username' => 'Test']);

        // assign role to user
        $this->visit('multi/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [1]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.role_users_table'), ['user_id' => 2, 'role_id' => 1]);

        $this->visit('multi/auth/logout')
            ->dontSeeIsAuthenticated('multi')
            ->seePageIs('multi/auth/login')
            ->submitForm('Login', ['username' => $user['username'], 'password' => $user['password']])
            ->see('dashboard')
            ->seeIsAuthenticated('multi')
            ->seePageIs('multi');

        $this->assertTrue($this->app['auth']->guard('multi')->getUser()->isMultiistrator());

        $this->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testUpdateUser()
    {
        $this->visit('multi/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['name' => 'test', 'roles' => [1]])
            ->seePageIs('multi/auth/users')
            ->seeInDatabase(config('multi.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'password'              => $password,
            'password_confirmation' => $password,
            'roles'                 => [1],
        ];

        $this->visit('multi/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', $data)
            ->seePageIs('multi/auth/users')
            ->visit('multi/auth/logout')
            ->dontSeeIsAuthenticated('multi')
            ->seePageIs('multi/auth/login')
            ->submitForm('Login', ['username' => $this->user->username, 'password' => $password])
            ->see('dashboard')
            ->seeIsAuthenticated('multi')
            ->seePageIs('multi');
    }
}
