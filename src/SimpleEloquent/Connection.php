<?php

namespace SimpleEloquent;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Connection
{
    /**
     * The connection name.
     * 
     * @var string
     */
    private $connection_name;

    /**
     * The eloquent connection.
     * 
     * @var \Illuminate\Database\Connection
     */
    private $connection;

    /**
     * The capsule associated with this connection.
     * 
     * @var Illuminate\Database\Capsule\Manager
     */
    private $capsule;

    /**
     * All active connections.
     * 
     * @var array
     */
    private static $connections = [];

    /**
     * Create a new connection.
     * 
     * @param string $name
     * @param string $config
     * 
     * @return SimpleEloquent\Connection
     */
    public static function set($name, $config)
    {
        $connection = new self;
        $connection->connection_name = $name;

        $connection->capsule = new Capsule;
        $connection->capsule->addConnection(
            $config,
            $connection->connection_name
        );
        $connection->capsule->setEventDispatcher(
            new Dispatcher(new Container)
        );
        $connection->capsule->bootEloquent();

        $connection->connection = $connection
                                  ->capsule
                                  ->getConnection(
                                      $connection->connection_name
                                  );
        self::$connections[$connection->connection_name] = $connection;

        return $connection;
    }

    /**
     * Retrieve a connection associated with the specified name.
     * 
     * @return SimpleEloquent\Connection|null
     */
    public static function get($name)
    {
        if (array_key_exists($name, self::$connections)) {
            return self::$connections[$name];
        }

        return null;
    }

    /**
     * Disconnect from this connection.
     */
    public function disconnect()
    {
        $this->connection->disconnect();
        unset(self::$connections[$this->connection_name]);
    }

    /**
     * Retrieve the Eloquent connection object
     * associated with this Connection.
     * 
     * @return \Illuminate\Database\Connection
     */
    public function eloquentConnection()
    {
        return $this->connection;
    }

    /**
     * Create a new model with a configuration.
     * 
     * Minimum Config:
     * 
     *     [
     *         'table' => 'TableName',
     *     ]
     * 
     * You can also add any other property outlined here:
     * https://laravel.com/api/5.7/Illuminate/Database/Eloquent/Model.html
     * 
     * @param array|string $config
     * @param array $attributes
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    public function model($config, array $attributes = [])
    {
        return new class (
            $attributes,
            $config,
            $this->connection_name,
            true
        ) 
            extends EloquentModel
        {
            public function __construct(
                $attributes = [],
                $config = null,
                $connection = null,
                $isSimple = false
            ) {
                parent::__construct($attributes);

                if ($isSimple) {
                    if (is_string($config)) {
                        $config = ['table' => $config];
                    }

                    if (! array_key_exists('table', $config))
                        die('SimpleEloquent: Missing \'table\' key.');

                    foreach ($config as $property => $value) {
                        if (! property_exists($this, "${property}")) {
                            trigger_error(
                                "SimpleEloquent: Unknown property in Model ".
                                "configuration array: '${property}'.",
                                E_USER_WARNING
                            );
                        }
                        $this->{$property} = $value;
                    }

                    if (! array_key_exists('connection', $config)) {
                        $this->connection = $connection;
                    }
                }
            }
        };
    }
}