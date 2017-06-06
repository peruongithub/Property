<?php
/*
 * This file is part of the Trident package.
 *
 * (c) Perederko Ruslan <perederko.ruslan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Components\Property\Interfaces;


interface PropertyInterface
{
    /**
     * @return string
     */
    public function getName():string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param null $value
     * @return PropertyInterface
     */
    public function setValue($value = null):PropertyInterface;

    /**
     * @param null $value
     * @return bool
     */
    public function isValidValue($value = null):bool;

    /**
     * @return mixed
     */
    public function getDefault();

    /**
     * @param null $default
     * @return PropertyInterface
     */
    public function setDefault($default = null):PropertyInterface;
}