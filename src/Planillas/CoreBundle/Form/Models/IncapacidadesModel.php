<?php

namespace Planillas\CoreBundle\Form\Models;

use Symfony\Component\Validator\Constraints as Assert;

class IncapacidadesModel
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $tipoIncapacidad;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $motivo;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @Assert\Date()
     */
    private $fechaFin;

    /**
     * @var $empleado \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @Assert\NotBlank()
     */
    private $empleado;

    /**
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     */
    public function setEmpleado($empleado)
    {
        $this->empleado = $empleado;
    }

    /**
     * @return \Planillas\CoreBundle\Entity\CEmpleado
     */
    public function getEmpleado()
    {
        return $this->empleado;
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

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $motivo
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    /**
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * @param string $tipoIncapacidad
     */
    public function setTipoIncapacidad($tipoIncapacidad)
    {
        $this->tipoIncapacidad = $tipoIncapacidad;
    }

    /**
     * @return string
     */
    public function getTipoIncapacidad()
    {
        return $this->tipoIncapacidad;
    }


} 