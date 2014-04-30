<?php

namespace Planillas\PaymentsBundle\Form\Models;

use Symfony\Component\Validator\Constraints as Assert;

class PeriodoPlanillaModel
{
    /**
     * @var  \DateTime $fechaInicio
     *
     * @Assert\Date()
     * @Assert\NotBlank()
     */
    private $fechaInicio;

    /**
     * @var  \DateTime $fechaFin
     *
     * @Assert\Date()
     * @Assert\NotBlank()
     */
    private $fechaFin;

    /**
     * @param \DateTime $fechaInicio
     * @param \DateTime $fechaFin
     */
    public function __construct(\DateTime $fechaInicio = null, \DateTime $fechaFin = null)
    {
        $this->fechaFin = $fechaFin;
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @param \DateTime $fechaFin
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * @param \DateTime $fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }


} 