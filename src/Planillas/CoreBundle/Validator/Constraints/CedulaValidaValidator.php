<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cinfante
 * Date: 06/07/13
 * Time: 01:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\CoreBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Security\Core\Util\StringUtils;

class CedulaValidaValidator extends ConstraintValidator{

    private $security;

    function __construct(SecurityContext $security)
    {
        $this->security = $security;
    }


    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($data, Constraint $constraint)
    {
        $securityPin        = $this->security->getToken()->getUser()->getPin();
        $cedula = $data->getCedula();
        if(!StringUtils::equals($choferCodigoBarras,'') && !StringUtils::equals($choferCodigoBarras,$data->getCodigobarras()) && !StringUtils::equals($securityPin,$data->getPin()))
            $this->context->addViolationAt('codigobarras',$constraint->messageBarras,array());

        if(!StringUtils::equals($choferPin,$data->getPin()) && !StringUtils::equals($securityPin,$data->getPin()))
            $this->context->addViolationAt('pin',$constraint->messagePin,array());

    }
}