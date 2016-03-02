<?php
namespace MichaelB\Database\DB2;

use Illuminate\Database\Query\Builder as QueryBuilder;
use PDO;
use Illuminate\Database\Connection;
use MichaelB\Database\DB2\Schema\Builder;
use MichaelB\Database\DB2\Query\Processors\DB2Processor;
use MichaelB\Database\DB2\Query\Grammars\DB2Grammar as QueryGrammar;
use MichaelB\Database\DB2\Schema\Grammars\DB2Grammar as SchemaGrammar;


class DB2Connection extends Connection
{

    /**
     * The name of the default schema.
     *
     * @var string
     */
    protected $defaultSchema;

    public function __construct(PDO $pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
        $this->currentSchema = $this->defaultSchema = strtoupper($config['schema']);
    }

    /**
     * @return QueryBuilder
     */
    public function query()
    {
        return new QueryBuilder(
          $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

    /**
     * @param DB2Procedure $procedure
     * @param array        $parameters
     *
     * @return mixed
     */
    public function callProcedure(DB2Procedure $procedure, $parameters = [])
    {
        $callable = $procedure->getProcedureName();
        $sql = "CALL $callable(";

        $format = $procedure->format();

        foreach ($format as $key => $value) {
            $sql .= "?,";
        }

        // remove last comma and add paren
        $sql = substr($sql, 0, strlen($sql) - 1).")";

        $outs = [];

        foreach ($procedure->outs as $key => $value) {
            $position = $procedure->getPositionOfParameter($key);
            $outs[$key] = $parameters[$position];
        }

        return $this->run($sql, $parameters, function ($me, $sql, $bindings) use ($outs, $procedure) {
            if ($me->pretending) {
                return ['pretending' => true];
            }

            $statement = $this->getPdo()->prepare($sql);

            $output = [];

            // Bind values of out parameters
            foreach ($outs as $key => $val) {
                $position = $procedure->getPositionOfParameter($key);
                $length = $procedure->getLengthOf($key);
                $type = $procedure->outs[$key];
                ${$key} = $bindings[$position];
                $output[$key] = &${$key};
                unset($bindings[$position]);

                $statement->bindParam($position + 1, ${$key}, $type, $length);
            }

            // Bind values of in paramters
            foreach($procedure->ins as $key => $type) {
                $position = $procedure->getPositionOfParameter($key);
                $value = $bindings[$position];

                $statement->bindValue($position + 1, $value, $type);
            }

            $statement->execute();

            return $output;
        });
    }

    /**
     * Get the name of the default schema.
     *
     * @return string
     */
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }

    /**
     * Reset to default the current schema.
     *
     * @return string
     */
    public function resetCurrentSchema()
    {
        return $this->setCurrentSchema($this->getDefaultSchema());
    }

    /**
     * Set the name of the current schema.
     *
     * @return string
     */
    public function setCurrentSchema($schema)
    {
        return $this->statement('SET SCHEMA ?', [strtoupper($schema)]);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) { $this->useDefaultSchemaGrammar(); }

        return new Builder($this);
    }

    /**
     * @return Query\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Default grammar for specified Schema
     * @return Schema\Grammars\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
    * Get the default post processor instance.
    *
    * @return \Illuminate\Database\Query\Processors\PostgresProcessor
    */
    protected function getDefaultPostProcessor()
    {
        return new DB2Processor;
    }

}
