# Simple Eloquent
> SimpleEloquent is a wrapper around Eloquent that makes common practices a little easier.

[![StyleCI](https://styleci.io/repos/163902694/shield?style=flat)](https://styleci.io/repos/163902694)
[![Latest Stable Version](https://poser.pugx.org/nafisc/simple-eloquent/v/stable?format=flat)](https://packagist.org/packages/nafisc/simple-eloquent)
[![Total Downloads](https://poser.pugx.org/nafisc/simple-eloquent/downloads?format=flat)](https://packagist.org/packages/nafisc/simple-eloquent)
[![Latest Unstable Version](https://poser.pugx.org/nafisc/simple-eloquent/v/unstable?format=flat)](https://packagist.org/packages/nafisc/simple-eloquent)
[![License](https://poser.pugx.org/nafisc/simple-eloquent/license?format=flat)](https://packagist.org/packages/nafisc/simple-eloquent)

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
