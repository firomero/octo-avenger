<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CIncapacidades
 *
 * @ORM\Table(name="c_incapacidades")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CIncapacidadesRepository")
 */
class CIncapacidades
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_incapacidad", type="string", nullable=false)
     * @Assert\NotBlank()
     */
    private $tipoIncapacidad;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", length=254, nullable=false)
     * @Assert\NotBlank()
     */
    private $motivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     * @Assert\NotBlank()
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     * @Assert\NotBlank()
     */
    private $fechaFin;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="incapacidades")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $empleado;

    /**
     * @var $planilla Planillas/CoreBundle/Entity/CPlanillas
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillas")
     */
    private $planilla;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipoIncapacidad
     *
     * @param string $tipoIncapacidad
     * @return CIncapacidades
     */
    public function setTipoIncapacidad($tipoIncapacidad)
    {
        $this->tipoIncapacidad = $tipoIncapacidad;
    
        return $this;
    }

    /**
     * Get tipoIncapacidad
     *
     * @return string 
     */
    public function getTipoIncapacidad()
    {
        return $this->tipoIncapacidad;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return CIncapacidades
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    
        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return CIncapacidades
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return CIncapacidades
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CIncapacidades
     */
    public function setEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return \Planillas\CoreBundle\Entity\CEmpleado 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }
    public function getJson()
    {
        $obj= new \stdClass();
        $obj->id=$this->id;
        $obj->fechaInicio=$this->fechaInicio->format('Y-m-d');
        $obj->fechaFin=$this->fechaFin->format('Y-m-d');
        $obj->tipoIncapacidad=$this->tipoIncapacidad;
        $obj->empleado=$this->empleado->getId();
        $obj->motivo=$this->motivo;


        return $obj;
    }

    /**
     * Set planilla
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @return CIncapacidades
     */
    public function setPlanilla(\Planillas\CoreBundle\Entity\CPlanillas $planilla = null)
    {
        $this->planilla = $planilla;
    
        return $this;
    }

    /**
     * Get planilla
     *
     * @return \Planillas\CoreBundle\Entity\CPlanillas 
     */
    public function getPlanilla()
    {
        return $this->planilla;
    }

    /**
     * @Assert\True(message = "Los valores entrados para las fechas no son correctos")
     */
    public function isFechasValid()
    {
        return $this->fechaInicio->getTimestamp() <= $this->fechaFin->getTimestamp() && $this->fechaFin->getTimestamp() <  time();
    }
}