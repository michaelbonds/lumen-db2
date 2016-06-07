<?php

namespace MichaelB\Database\DB2;


abstract class DB2Procedure
{
    /**
     * @var string
     */
    protected $library;

    /**
     * @var string
     */
    protected $connection;

    /**
     * @var string
     */
    protected $procedure;

    /**
     * @var array
     */
    protected $ins;

    /**
     * @var array
     */
    protected $outs;

    /**
     * @var int
     */
    protected $defaultLength = 0;

    /**
     * get the format to call procedure
     * @return array
     */
    public function format()
    {
        return array_merge($this->ins, $this->outs);
    }

    /**
     * @return DB2Connection
     */
    public function getConnection()
    {
        return $this->resolveConnection($this->connection);
    }

    /**
     * @param $key
     * @return int
     */
    public function getLengthOf($key)
    {
        $method = 'get'.ucwords($key).'Length';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return $this->defaultLength;
    }

    /**
     * @param $connection
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function resolveConnection($connection)
    {
        return app('db')->connection($connection);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getPositionOfParameter($key)
    {
        $format = $this->format();
        $keys = array_keys($format);
        return array_search($key, $keys);
    }


    /**
     * @param $ouput array
     * @return array
     */
    public function handleOutput($output = [])
    {
      return $output;
    }

    /**
     * @param array $arguments
     * @return static
     */
    public static function call($arguments = [])
    {
        $instance = new static;

        $connection = $instance->getConnection();
        $connection->setCurrentSchema($instance->getProcedureName());
        return $connection->callProcedure($instance, $arguments);
    }

    /**
     * @return string
     */
    public function getProcedureName()
    {
        $path = explode("\\", get_called_class());

        return ($this->procedure ?: array_pop($path));
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get'.ucwords($name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return $this->$name;
    }

    /**
     * call as function
     */
    public function __invoke()
    {
        return static::call(func_get_args());
    }
}
