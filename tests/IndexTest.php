<?php

use Micro\Multi\Auth\Database\Multiistrator;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Multiistrator::first(), 'multi');
    }

    public function testIndex()
    {
        $this->visit('multi/')
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
    }

    public function testClickMenu()
    {
        $this->visit('multi/')
            ->click('Users')
            ->seePageis('multi/auth/users')
            ->click('Roles')
            ->seePageis('multi/auth/roles')
            ->click('Permission')
            ->seePageis('multi/auth/permissions')
            ->click('Menu')
            ->seePageis('multi/auth/menu')
            ->click('Operation log')
            ->seePageis('multi/auth/logs');
    }
}
