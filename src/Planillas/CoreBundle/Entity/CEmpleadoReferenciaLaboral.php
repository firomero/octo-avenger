<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CEmpleadoReferenciaLaboral
 *
 * @ORM\Table(name="c_empleado_reflaboral")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CEmpleadoReferenciaLaboralRepository")
 */
class CEmpleadoReferenciaLaboral extends CEmpleadoReferencias
{
    /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string", length=255)
     */
    private $empresa;

    /**
     * @var string
     *
     * @ORM\Column(name="jefeInmediato", type="string", length=255)
     */
    private $jefeInmediato;

    /**
     * @var string
     *
     * @ORM\Column(name="puestoDesempennado", type="string", length=255)
     */
    private $puestoDesempennado;

    /**
     * @var string
     *
     * @ORM\Column(name="personaReferencia", type="string", length=255)
     */
    private $personaReferencia;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="date")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFinal", type="date")
     */
    private $fechaFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="motivoSalida", type="string", length=255)
     */
    private $motivoSalida;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recontratable", type="boolean")
     */
    private $recontratable;

    /**
     * @param string $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param \DateTime $fechaFinal
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;
    }

    /**
     * @return \DateTime
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
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
     * @param string $jefeInmediato
     */
    public function setJefeInmediato($jefeInmediato)
    {
        $this->jefeInmediato = $jefeInmediato;
    }

    /**
     * @return string
     */
    public function getJefeInmediato()
    {
        return $this->jefeInmediato;
    }

    /**
     * @param string $motivoSalida
     */
    public function setMotivoSalida($motivoSalida)
    {
        $this->motivoSalida = $motivoSalida;
    }

    /**
     * @return string
     */
    public function getMotivoSalida()
    {
        return $this->motivoSalida;
    }

    /**
     * @param string $personaReferencia
     */
    public function setPersonaReferencia($personaReferencia)
    {
        $this->personaReferencia = $personaReferencia;
    }

    /**
     * @return string
     */
    public function getPersonaReferencia()
    {
        return $this->personaReferencia;
    }

    /**
     * @param string $puestoDesempennado
     */
    public function setPuestoDesempennado($puestoDesempennado)
    {
        $this->puestoDesempennado = $puestoDesempennado;
    }

    /**
     * @return string
     */
    public function getPuestoDesempennado()
    {
        return $this->puestoDesempennado;
    }

    /**
     * @param boolean $recontratable
     */
    public function setRecontratable($recontratable)
    {
        $this->recontratable = $recontratable;
    }

    /**
     * @return boolean
     */
    public function getRecontratable()
    {
        return $this->recontratable;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }


}
