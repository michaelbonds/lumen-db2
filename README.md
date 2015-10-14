# laravel-db2

## This is an adaptation of [cooperl22's laravel-db2](https://www.github.com/cooperl22/laravel-db2) to work with [Lumen](http://lumen.laravel.com/)

### Installation
...

### Configuration

There are two ways to configure laravel-db2. You can choose the most convenient way for you. You can put your DB2 credentials into ``app/config/database.php`` file.

#### Configure DB2 using ``app/config/database.php`` file

Simply add this code at the end of your ``app/config/database.php`` file:

```php
    /*
    |--------------------------------------------------------------------------
    | DB2 Databases
    |--------------------------------------------------------------------------
    */

    'odbc' => [
        'driver'         => 'odbc',
        'host'           => '',
        'database'       => '',
        'username'       => '',
        'password'       => '',
        'charset'        => 'utf8',
        'ccsid'          => 1208,
        'prefix'         => '',
        'schema'         => '',
        'i5_libl'        => '',
        'i5_lib'         => '',
        'i5_commit'      => 0,
        'i5_naming'      => 0,
        'i5_date_fmt'    => 5,
        'i5_date_sep'    => 0,
        'i5_decimal_sep' => 0,
        'i5_time_fmt'    => 0,
        'i5_time_sep'    => 0,
        'options'  => [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
            ]
    ],

    'ibm' => [
        'driver'         => 'ibm',
        'host'           => '',
        'database'       => '',
        'username'       => '',
        'password'       => '',
        'charset'        => 'utf8',
        'ccsid'          => 1208,
        'prefix'         => '',
        'schema'         => '',
        'i5_libl'        => '',
        'i5_lib'         => '',
        'i5_commit'      => 0,
        'i5_naming'      => 0,
        'i5_date_fmt'    => 5,
        'i5_date_sep'    => 0,
        'i5_decimal_sep' => 0,
        'i5_time_fmt'    => 0,
        'i5_time_sep'    => 0,
        'options'  => [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
        ]
    ],

```
driver setting is either 'odbc' for ODBC connection or 'ibm' for pdo_ibm connection
Then if driver is 'odbc', database must be set to ODBC connection name.
if driver is 'ibm', database must be set to IBMi database name (WRKRDBDIRE).

## Usage

Consult the [Laravel framework documentation](http://laravel.com/docs).
