<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Truckee\MatchBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Validates area code length.
 *
 * @Annotation
 */
class AreaCode extends Constraint
{
    public $message = 'Area code must be 3 digits';

}
