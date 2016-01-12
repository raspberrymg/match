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
use Symfony\Component\Validator\ConstraintValidator;

class AreaCodeValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!(preg_match('/([0-9]{3})/', $value) || empty($value))) {
            $this->context->addViolation($constraint->message);

            return false;
        }

        return true;
    }
}
