<?php

class InstallTest extends TestCase
{
    public function testInstalledDirectories()
    {
        $this->assertFileExists(multi_path());

        $this->assertFileExists(multi_path('Controllers'));

        $this->assertFileExists(multi_path('routes.php'));

        $this->assertFileExists(multi_path('bootstrap.php'));

        $this->assertFileExists(multi_path('Controllers/HomeController.php'));

        $this->assertFileExists(multi_path('Controllers/AuthController.php'));

        $this->assertFileExists(multi_path('Controllers/ExampleController.php'));

        $this->assertFileExists(config_path('multi.php'));

        $this->assertFileExists(public_path('vendor/laravel-multi'));
    }
}
