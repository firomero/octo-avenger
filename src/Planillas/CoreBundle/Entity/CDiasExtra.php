<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CDiasExtra
 *
 * @ORM\Table(name="c_dias_extra")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CDiasExtraRepository")
 */
class CDiasExtra
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
     * @var \DateTime
     * @Assert\Date(),
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NMotivoDiaExtra")
     * @Assert\NotBlank()
     */
    private $motivo;


    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $empleado;

    /**
     * @var  \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillasEmpleado", inversedBy="diasExtras")
     */
    private $planillaEmpleado;


    public function getJson()
    {
        $obj= new \stdClass();
        $obj->id=$this->id;
        $obj->fecha=$this->fecha->format('Y-m-d');

        $obj->empleado=$this->empleado->getId();

        return $obj;
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
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CDiasExtra
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

    /**
     * Set fecha
     *
     * @param  \DateTime  $fecha
     * @return CDiasExtra
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
    * @Assert\True(message = "La fecha seleccionada no puede ser mayor que la fecha actual")
    */
    public function isFechaValid()
    {
       return $this->fecha->getTimestamp() <  time();        
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

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CDiasExtra
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return CDiasExtra
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
}