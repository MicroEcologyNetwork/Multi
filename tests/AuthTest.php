<?php

class AuthTest extends TestCase
{
    public function testLoginPage()
    {
        $this->visit('multi/auth/login')
            ->see('login');
    }

    public function testVisitWithoutLogin()
    {
        $this->visit('multi')
            ->dontSeeIsAuthenticated('multi')
            ->seePageIs('multi/auth/login');
    }

    public function testLogin()
    {
        $credentials = ['username' => 'multi', 'password' => 'multi'];

        $this->visit('multi/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'multi')
            ->seeIsAuthenticated('multi')
            ->seePageIs('multi')
            ->see('Dashboard')
            ->see('Description...')

            ->see('Environment')
            ->see('PHP version')
            ->see('Laravel version')

            ->see('Available extensions')
            ->seeLink('laravel-multi-ext/helpers', 'https://github.com/laravel-multi-extensions/helpers')
            ->seeLink('laravel-multi-ext/backup', 'https://github.com/laravel-multi-extensions/backup')
            ->seeLink('laravel-multi-ext/media-manager', 'https://github.com/laravel-multi-extensions/media-manager')

            ->see('Dependencies')
            ->see('php')
//            ->see('>=7.0.0')
            ->see('laravel/framework');

        $this
            ->see('<span>Multi</span>')
            ->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testLogout()
    {
        $this->visit('multi/auth/logout')
            ->seePageIs('multi/auth/login')
            ->dontSeeIsAuthenticated('multi');
    }
}
