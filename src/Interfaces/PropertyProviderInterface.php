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


interface PropertyProviderInterface
{
    /**
     * Return array of the property names
     * @return array
     */
    public function getProperties():array;

    public function hasProperty(string $name):bool;

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @return PropertyInterface|PropertyProviderInterface
     */
    public function getProperty(string $name):PropertyInterface;

    public function addProperty(PropertyInterface $property):PropertyProviderInterface;

    public function removeProperty(string $name):PropertyProviderInterface;

    public function __unset(string $name);

    /**
     * Get the value of the property
     * @param string $name
     * @return mixed
     */
    public function __get(string $name);

    /**
     * Sets the value of the property
     * @param string $name
     * @param null $value
     * @return mixed
     */
    public function __set(string $name, $value);
}