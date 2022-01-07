<?php

namespace MicroEcology\Multi\Console;

use MicroEcology\Multi\Auth\Database\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multi:permissions {--tables=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate multi permission base on table name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $all_tables = $this->getAllTables();

        $tables = $this->option('tables') ? explode(',', $this->option('tables')) : [];
        if (empty($tables)) {
            $ignore_tables = $this->getIgnoreTables();
            $tables = array_diff($all_tables, $ignore_tables);
        } else {
            $tables = array_intersect($all_tables, $tables);
        }

        if (empty($tables)) {
            $this->info('table is not existed');

            return;
        }

        $permissions = $this->getPermissions();
        foreach ($tables as $table) {
            foreach ($permissions as $permission => $permission_lang) {
                $http_method = $this->generateHttpMethod($permission);
                $http_path = $this->generateHttpPath($table, $permission);
                $slug = $this->generateSlug($table, $permission);
                $name = $this->generateName($table, $permission_lang);
                $exists = Permission::where('slug', $slug)->exists();
                if (!$exists) {
                    Permission::create([
                        'name'        => $name,
                        'slug'        => $slug,
                        'http_method' => $http_method,
                        'http_path'   => $http_path,
                    ]);
                    $this->info("$slug is generated");
                } else {
                    $this->warn("$slug is existed");
                }
            }
        }
    }

    private function getAllTables()
    {
        return array_map('current', DB::select('SHOW TABLES'));
    }

    private function getIgnoreTables()
    {
        return [
            config('multi.database.users_table'),
            config('multi.database.roles_table'),
            config('multi.database.permissions_table'),
            config('multi.database.menu_table'),
            config('multi.database.operation_log_table'),
            config('multi.database.user_permissions_table'),
            config('multi.database.role_users_table'),
            config('multi.database.role_permissions_table'),
            config('multi.database.role_menu_table'),
        ];
    }

    private function getPermissions()
    {
        return [
            'list'   => __('multi.list'),
            'view'   => __('multi.view'),
            'create' => __('multi.create'),
            'edit'   => __('multi.edit'),
            'delete' => __('multi.delete'),
            'export' => __('multi.export'),
            'filter' => __('multi.filter'),
        ];
    }

    private function generateHttpMethod($permission)
    {
        switch ($permission) {
            case 'create':
                $http_method = ['POST'];
                break;
            case 'edit':
                $http_method = ['PUT', 'PATCH'];
                break;
            case 'delete':
                $http_method = ['DELETE'];
                break;
            default:
                $http_method = ['GET'];
        }

        return $http_method;
    }

    private function generateHttpPath($table, $permission)
    {
        $resource = Str::kebab(Str::camel($table));
        switch ($permission) {
            case 'create':
            case 'list':
            case 'filter':
                $http_path = '/'.$resource;
                break;
            case 'edit':
            case 'delete':
            case 'view':
                $http_path = '/'.$resource.'/*';
                break;
            default:
                $http_path = '';
        }

        return $http_path;
    }

    private function generateSlug($table, $permission)
    {
        return Str::kebab(Str::camel($table)).'.'.$permission;
    }

    private function generateName($table, $permission_lang)
    {
        return Str::upper(Str::kebab(Str::camel($table))).$permission_lang;
    }
}
