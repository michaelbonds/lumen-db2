<?php
namespace MichaelB\Database\DB2;

use MichaelB\Database\DB2\Connectors\ODBCConnector;
use MichaelB\Database\DB2\Connectors\IBMConnector;
use MichaelB\Database\DB2\Console\Commands\PublishCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Contracts\Console\Kernel;

class DB2ServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge our config 'defaults'
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'laravel-db2');
        $database_connections = config('database.connections');

        //Extend the connections with pdo_odbc and pdo_ibm drivers
        foreach($database_connections as $connection => $config)
        {
            switch ($config['driver']) {
                case 'odbc':
                    $connector = new ODBCConnector();
                    break;

                case 'ibm':
                    $connector = new IBMConnector();
                    break;

                default:
                    continue 2;
            }

            //Create a connector
            $this->app['db']->extend($connection, function($config) use ($connector) {
                $config = array_replace_recursive(config('laravel-db2'), $config);
                $db2Connection = $connector->connect($config);
                return new DB2Connection($db2Connection, $config["database"], $config["prefix"], $config);
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
