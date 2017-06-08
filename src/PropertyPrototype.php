<?php
/*
 * This file is part of the Trident package.
 *
 * (c) Perederko Ruslan <perederko.ruslan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Components\Property;


use Trident\Components\Property\Interfaces\PropertyInterface;
use Trident\Components\Property\Traits\PropertyTrait;

class PropertyPrototype implements PropertyInterface
{
    use PropertyTrait;

    final function setName(string $name):PropertyInterface{
        if(null !== $this->name){
            throw new \LogicException(
                sprintf('You cannot set property name, because is already exist in this class "%s".', get_class($this))
            );
        }
        $this->name = $name;

        return $this;
    }
}