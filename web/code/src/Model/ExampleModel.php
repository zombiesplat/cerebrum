<?php

declare(strict_types=1);

namespace Example\Model;

use Mini\Model\AbstractModel;

/**
 * Example data.
 *
 * @property int $id
 * @property int $example_id
 * @property string $created
 * @property string $code
 * @property string $description
 * @method fill(array $attributes) : AbstractModel
 * @method select(int $id) : array
 * @method insert(): int
 */
class ExampleModel extends AbstractModel
{
    /**
     * Base table for model
     * @var string
     */
    protected string $table = 'master_example';

    /**
     * Model's Primary Key
     *
     * @var string
     */
    protected string $primaryKey = 'example_id';

    /**
     * The model's columns in the database
     *
     * @var array
     */
    protected array $fillable = [
        'created',
        'code',
        'description',
    ];
}
