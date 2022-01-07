<?php

use Micro\Multi\Auth\Database\Multiistrator;
use Micro\Multi\Auth\Database\OperationLog;

class OperationLogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Multiistrator::first(), 'multi');
    }

    public function testOperationLogIndex()
    {
        $this->visit('multi/auth/logs')
            ->see('Operation log')
            ->see('List')
            ->see('GET')
            ->see('multi/auth/logs');
    }

    public function testGenerateLogs()
    {
        $table = config('multi.database.operation_log_table');

        $this->visit('multi/auth/menu')
            ->seePageIs('multi/auth/menu')
            ->visit('multi/auth/users')
            ->seePageIs('multi/auth/users')
            ->visit('multi/auth/permissions')
            ->seePageIs('multi/auth/permissions')
            ->visit('multi/auth/roles')
            ->seePageIs('multi/auth/roles')
            ->visit('multi/auth/logs')
            ->seePageIs('multi/auth/logs')
            ->seeInDatabase($table, ['path' => 'multi/auth/menu', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'multi/auth/users', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'multi/auth/permissions', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'multi/auth/roles', 'method' => 'GET']);

        $this->assertEquals(4, OperationLog::count());
    }

    public function testDeleteLogs()
    {
        $table = config('multi.database.operation_log_table');

        $this->visit('multi/auth/logs')
            ->seePageIs('multi/auth/logs')
            ->assertEquals(0, OperationLog::count());

        $this->visit('multi/auth/users');

        $this->seeInDatabase($table, ['path' => 'multi/auth/users', 'method' => 'GET']);

        $this->delete('multi/auth/logs/1')
            ->assertEquals(0, OperationLog::count());
    }

    public function testDeleteMultipleLogs()
    {
        $table = config('multi.database.operation_log_table');

        $this->visit('multi/auth/menu')
            ->visit('multi/auth/users')
            ->visit('multi/auth/permissions')
            ->visit('multi/auth/roles')
            ->seeInDatabase($table, ['path' => 'multi/auth/menu', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'multi/auth/users', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'multi/auth/permissions', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'multi/auth/roles', 'method' => 'GET'])
            ->assertEquals(4, OperationLog::count());

        $this->delete('multi/auth/logs/1,2,3,4')
            ->notSeeInDatabase($table, ['path' => 'multi/auth/menu', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'multi/auth/users', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'multi/auth/permissions', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'multi/auth/roles', 'method' => 'GET'])

            ->assertEquals(0, OperationLog::count());
    }
}
