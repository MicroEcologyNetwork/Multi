<?php

use Micro\Multi\Auth\Database\Multiistrator;
use Micro\Multi\Auth\Database\Menu;

class MenuTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Multiistrator::first(), 'multi');
    }

    public function testMenuIndex()
    {
        $this->visit('multi/auth/menu')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Roles')
            ->see('Permission')
            ->see('Menu');
    }

    public function testAddMenu()
    {
        $item = ['parent_id' => '0', 'title' => 'Test', 'uri' => 'test'];

        $this->visit('multi/auth/menu')
            ->seePageIs('multi/auth/menu')
            ->see('Menu')
            ->submitForm('Submit', $item)
            ->seePageIs('multi/auth/menu')
            ->seeInDatabase(config('multi.database.menu_table'), $item)
            ->assertEquals(8, Menu::count());

//        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);
//
//        $this->visit('multi')
//            ->see('Test')
//            ->click('Test');
    }

    public function testDeleteMenu()
    {
        $this->delete('multi/auth/menu/8')
            ->assertEquals(7, Menu::count());
    }

    public function testEditMenu()
    {
        $this->visit('multi/auth/menu/1/edit')
            ->see('Menu')
            ->submitForm('Submit', ['title' => 'blablabla'])
            ->seePageIs('multi/auth/menu')
            ->seeInDatabase(config('multi.database.menu_table'), ['title' => 'blablabla'])
            ->assertEquals(7, Menu::count());
    }

    public function testShowPage()
    {
        $this->visit('multi/auth/menu/1/edit')
            ->seePageIs('multi/auth/menu/1/edit');
    }

    public function testEditMenuParent()
    {
        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('multi/auth/menu/5/edit')
            ->see('Menu')
            ->submitForm('Submit', ['parent_id' => 5]);
    }
}
