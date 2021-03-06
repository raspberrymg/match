<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Validator\Constraints\PhoneNumber.php

namespace Truckee\MatchBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * PhoneNumber.
 *
 * @Annotation
 */
class PhoneNumber extends Constraint
{
    public $message = 'Phone must be like 123-4567';

}
