<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\PhoneNumber.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * PhoneNumber
 * @Annotation
 */
class PhoneNumber extends Constraint
{
    public $message = 'Phone must be like 123-4567';

}
