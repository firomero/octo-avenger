<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 17/05/14
 * Time: 04:38 PM
 */

namespace Planillas\CoreBundle\Twig;

use Planillas\CoreBundle\Entity\CEmpleadoReferenciaLaboral;
use Planillas\CoreBundle\Entity\CEmpleadoReferenciaPersonal;

class EmpleadoReferenciasTypeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('referencia_type', array($this, 'getReferenciaType'))
        );
    }

    public function getReferenciaType($referencia)
    {
        if(!is_object($referencia))
            throw new \Exception('El valor debe ser un objeto.');

        if($referencia instanceof CEmpleadoReferenciaLaboral)
            return 'Referencia Laboral';

        if($referencia instanceof CEmpleadoReferenciaPersonal)
            return 'Referencia Personal';

        return 'Desconocido';
    }

    public function getName()
    {
        return 'empleado_referencia_type';
    }
} 