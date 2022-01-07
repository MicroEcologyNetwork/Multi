# Upgrade precautions

Because `laravel-multi 1.5` built-in table structure has been modified, it is recommended that you re-install `laravel 5.5` and `laravel-multi 1.5`, and then migrate the code over

Code migration needs attention：

- Please refer to the table structure changes [tables.php](https://github.com/z-song/laravel-multi/blob/master/database/migrations/2016_01_04_173148_create_multi_tables.php)
- Routing file structure is modified please refer to [routes.stub](https://github.com/z-song/laravel-multi/blob/master/src/Console/stubs/routes.stub)
- Please refer to the configuration file structure changes [multi.php](https://github.com/z-song/laravel-multi/blob/master/config/multi.php)
- The chart component has been removed and can no longer be used, please refer to [Custom chart](/en/custom-chart.md)