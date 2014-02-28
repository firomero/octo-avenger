<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateRangeValidator
 *
 * @author Jose Mojena
 */

namespace Planillas\EntidadesBundle\Validator\Constrainst;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateRangeValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {
        if (null === $value || '' === $value) {
            return;
        }
        
        $fechaInicio=$constraint->getFechaInicio();
        $fechaFin   =$constraint->getFechaFin();
    }

}

?>
