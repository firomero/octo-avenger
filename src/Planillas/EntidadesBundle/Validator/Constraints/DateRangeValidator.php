<?php
/**
 * Description of DateRangeValidator
 *
 * @author Jose Mojena
 */

namespace Planillas\EntidadesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateRangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $fechaInicio=$constraint->getFechaInicio();
        $fechaFin   =$constraint->getFechaFin();
    }

}
