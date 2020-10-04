<?php

declare(strict_types=1);

namespace Example\Model;

use Mini\Model\HasAttributesTrait;

/**
 * Class AdditionModel
 * @package Example\Model
 *
 * @property  mixed $addend_left
 * @property  mixed $addend_right
 */
class AdditionModel
{
    use HasAttributesTrait;

    /**
     * Performs addition of the left and right addends
     *
     * @return mixed
     */
    public function sum()
    {
        return $this->addend_left + $this->addend_right;
    }

    /**
     * Quick check to validate the ability to sum
     *
     * @return bool
     */
    public function canSum()
    {
        return is_numeric($this->addend_left) && is_numeric($this->addend_right);
    }
}