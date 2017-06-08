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
use Trident\Components\Property\PropertyPrototype;

trait PropertyProviderTrait
{
    /**
     * @var $properties array
     * [name=>[
     *          type=>className,
     *          value=>value of the property,
     *          default=>default value of the property,
     *          arguments=>arguments for the constructor,
     *          obj=>object PropertyInterface
     * ]...]
     */
    protected $properties = [];

    /**
     * @var PropertyPrototype
     */
    protected $propertyPrototype;

    /**
     * @inheritdoc
     */
    public function hasProperty(string $name): bool
    {
        return array_key_exists($name, $this->properties);
    }

    /**
     * @inheritdoc
     */
    public function getProperties(): array
    {
        return array_keys($this->properties);
    }

    /**
     * @return PropertyPrototype
     */
    protected function getPropertyPrototype():PropertyPrototype{
        if(!($this->propertyPrototype instanceof PropertyInterface)){
            $this->propertyPrototype = new PropertyPrototype();
        }

        return clone $this->propertyPrototype;
    }

    /**
     * @inheritdoc
     */
    public function getProperty(string $name): PropertyInterface
    {
        if (!isset($this->properties[$name])) {
            throw new \InvalidArgumentException(
                sprintf('Property "%s" don`t exist in class "%s".', $name, get_class($this))
            );
        } elseif (!empty($this->properties[$name]['obj']) && $this->properties[$name]['obj'] instanceof PropertyInterface) {
            return $this->properties[$name]['obj'];
        }

        if (!empty($this->properties[$name]['type'])) {
            $type = $this->properties[$name]['type'];


            if (!class_exists($type)) {
                throw new \RuntimeException(
                    sprintf('Class "%s" for Property "%s" don`t exist.', $type, $name)
                );
            }

            if (!empty($this->properties[$name]['arguments'])) {
                $reflection = new \ReflectionClass($type);
                $property = call_user_func_array([$reflection, 'newInstance'], $this->properties[$name]['arguments']);
            } else {
                $property = new $type();
            }
        } else {
            $property = $this->getPropertyPrototype();
            $property->setName($name);
        }

        $property
            ->setValue(
                !empty($this->properties[$name]['value'])?
                    $this->properties[$name]['value']:
                    null
            )
            ->setDefault(
                !empty($this->properties[$name]['default'])?
                    $this->properties[$name]['default']:
                    null
            );
        /** @var $obj PropertyInterface */

        return $this->properties[$name]['obj'] = $property;
    }

    /**
     * @inheritdoc
     */
    public function __get(string $name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else if ($this->hasProperty($name)) {
            return $this->getProperty($name)->getValue();
        }
        throw new \InvalidArgumentException(
            sprintf('Property "%s" don`t exist in class "%s".', $name, get_class($this))
        );
    }

    /**
     * @inheritdoc
     */
    public function __set(string $name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            return $this->$setter($value);
        } else if ($this->hasProperty($name)) {
            return $this->getProperty($name)->setValue($value);
        }
        throw new \InvalidArgumentException(
            sprintf('Property "%s" don`t exist in class "%s".', $name, get_class($this))
        );
    }

    /**
     * @inheritdoc
     */
    public function addProperty(PropertyInterface $property): PropertyProviderInterface
    {
        $name = $property->getName();

        if ($this->hasProperty($name)) {
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

    /**
     * @inheritdoc
     */
    public function removeProperty(string $name): PropertyProviderInterface
    {
        if ($this->hasProperty($name)) {
            unset($this->properties[$name]);
        }
        /** @var $this PropertyProviderInterface */
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function __unset(string $name)
    {
        $this->removeProperty($name);
    }
}