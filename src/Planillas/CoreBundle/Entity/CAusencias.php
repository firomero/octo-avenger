<?php

namespace Planillas\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * CAusencias
 *
 * @ORM\Table(name="c_ausencias")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CAusenciasRepository")
 */
class CAusencias
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
     * @ORM\Column(name="tipo_ausencia", type="string", nullable=false)
     * @Assert\NotBlank()
     */
    private $tipoAusencia;

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
     * @Assert\Date()
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $fechaFin;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="ausencias")
     * @Assert\NotBlank()
     */
    private $empleado;

    /**
     * @var  Planillas/CoreBundle/Entity/CPlanillasEmpleado $planillaEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillasEmpleado", inversedBy="ausencias")
     */
    private $planillaEmpleado;

    /**
     * @Assert\True(message = "Los valores entrados para las fechas no son correctos")
     */
    public function isFechasValid()
    {
        return $this->fechaInicio->getTimestamp() <= $this->fechaFin->getTimestamp() && $this->fechaFin->getTimestamp() <  time();
    }

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
     * Set tipoAusencia
     *
     * @param string $tipoAusencia
     * @return CAusencias
     */
    public function setTipoAusencia($tipoAusencia)
    {
        $this->tipoAusencia = $tipoAusencia;
    
        return $this;
    }

    /**
     * Get tipoAusencia
     *
     * @return string 
     */
    public function getTipoAusencia()
    {
        return $this->tipoAusencia;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return CAusencias
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return CAusencias
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
     * @return CAusencias
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
     * @return CAusencias
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
     * @return CAusencias
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
        $obj->empleado=$this->empleado->getId();
        $obj->tipoAusencia=$this->tipoAusencia;
        $obj->motivo=$this->motivo;
        return $obj;
    }

    /**
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     */
    public function setPlanillaEmpleado(CPlanillasEmpleado $planillaEmpleado)
    {
        $this->planillaEmpleado = $planillaEmpleado;
    }

    /**
     * @return \Planillas\CoreBundle\Entity\CPlanillasEmpleado
     */
    public function getPlanillaEmpleado()
    {
        return $this->planillaEmpleado;
    }
}