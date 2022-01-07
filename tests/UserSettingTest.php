<?php

use MicroEcology\Multi\Auth\Database\Multiistrator;
use Illuminate\Support\Facades\File;

class UserSettingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Multiistrator::first(), 'multi');
    }

    public function testVisitSettingPage()
    {
        $this->visit('multi/auth/setting')
            ->see('User setting')
            ->see('Username')
            ->see('Name')
            ->see('Avatar')
            ->see('Password')
            ->see('Password confirmation');

        $this->seeElement('input[value=Multiistrator]')
            ->seeInElement('.box-body', 'multiistrator');
    }

    public function testUpdateName()
    {
        $data = [
            'name' => 'tester',
        ];

        $this->visit('multi/auth/setting')
            ->submitForm('Submit', $data)
            ->seePageIs('multi/auth/setting');

        $this->seeInDatabase('multi_users', ['name' => $data['name']]);
    }

    public function testUpdateAvatar()
    {
        File::cleanDirectory(public_path('uploads/images'));

        $this->visit('multi/auth/setting')
            ->attach(__DIR__.'/assets/test.jpg', 'avatar')
            ->press('Submit')
            ->seePageIs('multi/auth/setting');

        $avatar = Multiistrator::first()->avatar;

        $this->assertEquals('http://localhost:8000/uploads/images/test.jpg', $avatar);
    }

    public function testUpdatePasswordConfirmation()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123',
        ];

        $this->visit('multi/auth/setting')
            ->submitForm('Submit', $data)
            ->seePageIs('multi/auth/setting')
            ->see('The Password confirmation does not match.');
    }

    public function testUpdatePassword()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('multi/auth/setting')
            ->submitForm('Submit', $data)
            ->seePageIs('multi/auth/setting');

        $this->assertTrue(app('hash')->check($data['password'], Multiistrator::first()->makeVisible('password')->password));

        $this->visit('multi/auth/logout')
            ->seePageIs('multi/auth/login')
            ->dontSeeIsAuthenticated('multi');

        $credentials = ['username' => 'multi', 'password' => '123456'];

        $this->visit('multi/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'multi')
            ->seeIsAuthenticated('multi')
            ->seePageIs('multi');
    }
}
