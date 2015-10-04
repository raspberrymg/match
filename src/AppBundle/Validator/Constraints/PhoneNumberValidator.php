<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
 
class PhoneNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $a = preg_match('/([0-9]{3})[ .-]([0-9]{4})/', $value);
        $b = empty($value);
        if (!($a || $b)) {
 
            $this->context->addViolation($constraint->message);
 
            return false;
        }
 
        return true;
    }
}
