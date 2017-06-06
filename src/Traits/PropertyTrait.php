<?php
/*
 * This file is part of the Trident package.
 *
 * (c) Perederko Ruslan <perederko.ruslan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Components\Property\Traits;


use Trident\Components\Property\Interfaces\PropertyInterface;

trait PropertyTrait
{
    protected $name;

    protected $value;

    protected $default;

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     * @return PropertyInterface
     */
    public function setValue($value = null):PropertyInterface
    {
        if (!$this->isValidValue($value)) {
            throw new \InvalidArgumentException('Value is not valid.');
        }

        $this->value = $value;

        /** @var $this PropertyInterface */
        return $this;
    }

    /**
     * @param null $value
     * @return bool
     */
    public function isValidValue($value = null):bool
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null $default
     * @return PropertyInterface
     */
    public function setDefault($default = null):PropertyInterface
    {
        $this->default = $default;

        /** @var $this PropertyInterface */
        return $this;
    }
}