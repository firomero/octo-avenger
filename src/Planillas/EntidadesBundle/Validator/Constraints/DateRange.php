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

namespace Planillas\EntidadesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class DateRange extends Constraint
{
    /**
     * @var string
     */
    public $message = 'La fecha "%fechaInicio%" no puede ser mayor que a la fecha "%fechaFin%"';

    /**
     * @var string
     */
    public $fechaInicio;
    public function getFechaInicio()
    {
        return  $this->fechaInicio;
    }

    /**
     * @var string
     */
    public $fechaFin;
    public function getFechaFin()
    {
        return  $this->fechaFin;
    }

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

}
