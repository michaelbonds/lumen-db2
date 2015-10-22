## This is an adaptation of [cooperl22's laravel-db2](https://www.github.com/cooperl22/laravel-db2) to work with [Lumen](http://lumen.laravel.com/)

# Lumen-DB2

### Installation
Install lumen-db2 via composer:

```sh
composer require michaelb/lumen-db2
```

Uncomment call to Eloquent and add the DB2ServiceProvideer to ``bootstrap/app.php``:

```php
// ...

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

// $app->withFacades();

$app->withEloquent(); // <- Uncomment this

// ...

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(MichaelB\Database\DB2\DB2ServiceProvider::class); // <- Add this

```

### Configuration

Create the ``app/config/database.php`` file:

```php

/*
 |--------------------------------------------
 | Configuration Defaults
 |--------------------------------------------
 */

return [

    'connections' => [

        'as400' => [

            'driver'               => 'odbc',

             // General settings
            'host'                 => '',
            'username'             => '',
            'password'             => '',

            //Server settings
            'database'             => '',
            'prefix'               => '',
            'schema'               => '',
            'signon'               => 3,
            'ssl'                  => 0,
            'commitMode'           => 2,
            'connectionType'       => 0,
            'defaultLibraries'     => '',
            'naming'               => 0,
            'unicodeSql'           => 0,

            // Format settings
            'dateFormat'           => 5,
            'dateSeperator'        => 0,
            'decimal'              => 0,
            'timeFormat'           => 0,
            'timeSeparator'        => 0,

            // Performances settings
            'blockFetch'           => 1,
            'blockSizeKB'          => 32,
            'allowDataCompression' => 1,
            'concurrency'          => 0,
            'lazyClose'            => 0,
            'maxFieldLength'       => 15360,
            'prefetch'             => 0,
            'queryTimeout'         => 1,

            // Modules settings
            'defaultPkgLibrary'    => '',
            'defaultPackage'       => '',
            'extendedDynamic'      => 1,

            // Diagnostic settings
            'QAQQINILibrary'       => '',
            'sqDiagCode'           => '',

            // Sort settings
            'languageId'           => '',
            'sortTable'            => '',
            'sortSequence'         => 0,
            'sortWeight'           => 0,
            'jobSort'              => 0,

            // Conversion settings
            'allowUnsupportedChar' => 0,
            'ccsid'                => 1208,
            'graphic'              => 0,
            'forceTranslation'     => 0,

            // Other settings
            'allowProcCalls'       => 0,
            'DB2SqlStates'         => 0,
            'debug'                => 0,
            'trueAutoCommit'       => 0,
            'catalogOptions'       => 3,
            'libraryView'          => 0,
            'ODBCRemarks'          => 0,
            'searchPattern'        => 1,
            'translationDLL'       => '',
            'translationOption'    => 0,
            'maxTraceSize'         => 0,
            'multipleTraceFiles'   => 1,
            'trace'                => 0,
            'traceFilename'        => '',
            'extendedColInfo'      => 0,
            'options'  => [
                PDO::ATTR_CASE => PDO::CASE_LOWER,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ]
        ]
    ]
];



```
driver setting is either 'odbc' for ODBC connection or 'ibm' for pdo_ibm connection
Then if driver is 'odbc', database must be set to ODBC connection name.
if driver is 'ibm', database must be set to IBMi database name (WRKRDBDIRE).

## Usage

Consult the [Laravel framework documentation](http://laravel.com/docs).
