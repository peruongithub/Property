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
use Trident\Components\Property\Interfaces\PropertyProviderInterface;

trait PropertyProviderTrait
{
    /**
     * @var $properties array
     * [name=>[type=>className, arguments=>arguments for constructor, obj=>PropertyInterface]]
     */
    protected $properties = [];

    public function hasProperty(string $name):bool
    {
        return array_key_exists($name, $this->properties);
    }

    public function getProperties():array
    {
        return array_keys($this->properties);
    }

    public function getProperty(string $name):PropertyInterface
    {
        if (!isset($this->properties[$name])) {
            throw new \InvalidArgumentException(
                sprintf('Property "%s" don`t exist in class "%s".', $name, get_class($this))
            );
        } elseif (!empty($this->properties[$name]['obj']) && $this->properties[$name]['obj'] instanceof PropertyInterface) {
            return $this->properties[$name]['obj'];
        }

        $type = 'Trident\\Components\\Property\\Property';
        if (!empty($this->properties[$name]['type'])) {
            $type = $this->properties[$name]['type'];
        }

        if(!class_exists($type)){
            throw new \RuntimeException(
                sprintf('Class "%s" for Property "%s" don`t exist.', $type, $name)
            );
        }

        if (!empty($this->properties[$name]['arguments'])) {
            $reflection = new \ReflectionClass($type);
            $obj = call_user_func_array([$reflection, 'newInstance'], $this->properties[$name]['arguments']);
        } else {
            $obj = new $type();
        }

        /** @var $obj PropertyInterface */

        return $this->properties[$name]['obj'] = $obj;
    }

    public function __get(string $name):PropertyInterface
    {
        return $this->getProperty($name);
    }

    public function addProperty(PropertyInterface $property):PropertyProviderInterface
    {
        $name = $property->getName();

        if($this->hasProperty($name)){
            throw new \RuntimeException(
                sprintf('Property with name "%s" already exist.', $name)
            );
        }

        $this->properties[$name] = [
            'type' => get_class($property),
            'arguments' => [],
            'obj' => $property
        ];

        /** @var $this PropertyProviderInterface */
        return $this;
    }

    public function removeProperty(string $name):PropertyProviderInterface
    {
        if($this->hasProperty($name)){
            unset($this->properties[$name]);
        }
        /** @var $this PropertyProviderInterface */
        return $this;
    }
}