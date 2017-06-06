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

class Property implements PropertyInterface
{
    use PropertyTrait;

    public function __construct(string $name, $value = null, $default = null)
    {
        $this->name = $name;
        $this->setValue($value)->setDefault($default);
    }
}