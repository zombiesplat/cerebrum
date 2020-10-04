<?php

declare(strict_types = 1);

namespace Mini\Model;

use ArrayAccess;
use Exception;
use Mini\Database\Database;
use Mini\Database\MySql;
use Mini\Database\MySqlConnection;
use Mini\Database\MySqlManager;

/**
 * Wrapper for database models.
 */
abstract class AbstractModel implements ArrayAccess
{
    use HasAttributesTrait;
    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected $schema;

    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Database object.
     * 
     * @var Database|MySql|MySqlManager|MySqlConnection|null
     */
    protected $db = null;

    /**
     * The model's columns in the database
     *
     * @var array
     */
    protected array $fillable = [];

    /**
     * Setup common objects that the models can use.
     */
    public function __construct()
    {
        if (empty($this->schema)) {
            $this->schema = getenv('DB_SCHEMA');
        }
        $this->db = container(Database::class);
    }

    /**
     * Start a transaction.
     * 
     * Note: this would be for some class aside from the model that is starting
     * and commiting the transaction since it can't directly access the db object.
     *
     * @param string $isolationLevel optional isolation level override for the transaction
     * 
     * @return void
     */
    public function startTransaction(string $isolationLevel = 'SERIALIZABLE'): void
    {
        $this->db->startTransaction($isolationLevel);
    }

    /**
     * Commit a transaction.
     * 
     * Note: this would be for some class aside from the model that is starting
     * and commiting the transaction since it can't directly access the db object
     *
     * @return void
     */
    public function commitTransaction(): void
    {
        $this->db->commitTransaction();
    }

    /**
     * Rollback a transaction.
     * 
     * Note: this would be for some class aside from the model that is rolling back
     * the transaction since it can't directly access the db object
     *
     * @return void
     */
    public function rollbackTransaction(): void
    {
        $this->db->rollbackTransaction();
    }

    /**
     * Check if we are currently inside a transaction.
     * 
     * @return bool flag if we are in a transaction or not
     */
    public function inTransaction(): bool
    {
        return $this->db->inTransaction();
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Execute a select query on the object's table
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function select(int $id): array
    {
        $sql = "SELECT `{$this->primaryKey}` AS id,
                       `{$this->table}`.*
                 FROM `{$this->schema}`.`{$this->table}`
                 WHERE `{$this->primaryKey}` = ?";
        return $this->db->select([
            'sql' => $sql,
            'inputs' => [$id]
        ]);
    }

    /**
     * Gets attributes from DB and fills object with results
     *
     * @param int $id
     * @return $this
     * @throws Exception
     */
    public function get(int $id): self
    {
        $attributes = $this->select($id);
        $this->fill($attributes);

        return $this;
    }

    /**
     * Insert a new record to the database
     *
     * @return int
     * @throws Exception
     */
    public function insert(): int
    {
        $attribute_names = join(',', $this->fillable);
        $attribute_value_places = join(',', array_fill(0, count($this->fillable), '?'));
        $values = $this->attributes;
        $attribute_values = array_map(
            function ($key) use ($values) {
                return $values[$key];
            },
            $this->fillable
        );

        $sql = "
            INSERT INTO `{$this->schema}`.`{$this->table}`
            ({$attribute_names})
            VALUES ({$attribute_value_places})";

        $id = $this->db->statement([
            'sql' => $sql,
            'inputs' => $attribute_values
        ]);

        $this->db->validateAffected();
        $this->setAttribute($this->primaryKey, $id);
        $this->setAttribute('id', $id);
        return $id;
    }
}
