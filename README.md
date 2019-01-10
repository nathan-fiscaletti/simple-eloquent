# Simple Eloquent
> SimpleEloquent is a wrapper around Eloquent that makes common practices a little easier.

## Installation

SimpleEloquent is available through composer.

```
composer require nafisc/simple-eloquent
```

## Usage

### Initializing a Connection

```php
    use \SimpleEloquent\Connection;
    
    $connection = Connection::set (

        // Connection Name
        'test', 

        // Configuration
        [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => 'password',
        ]

    );
```

### Executing Sql / Accessing Eloquent Connection

```php
    // Retrieve the Connection
    $connection = Connection::get('test');

    // Execute some Sql
    $result = $connection->select('SELECT * FROM `servers` LIMIT 1');
    $server = $result[0];
    echo $server->ipv4.PHP_EOL;

    . . . 

    // Alternately, you can access the eloquent
    // connection object directly.
    $connection = Connection::get('test')->eloquentConnection();
    $result = $connection->select('SELECT * FROM `servers` LIMIT 1');
```

### Using Models

```php
    // Retrieve the connection.
    $connection = Connection::get('test');

    // Create a new model for the table 'servers'.
    // See \SimpleEloquent\Connection@model function for more documentation.
    $model = $connection->model('servers');

    // Retrieve a result.
    $result = $model->where('id', 2)->first();
    echo $result->ipv4.PHP_EOL;
```
