<?php

namespace Planillas\CoreBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * CHorasExtras
 *
 * @ORM\Table(name="c_horas_extras")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CHorasExtrasRepository")
 */
class CHorasExtras
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
     * @ORM\Column(name="cantidad_horas", type="string", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^([1-9]+)$/", message="La cantidad de horas extras no es correcta")
     */
    private $cantidadHoras;

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
     * @ORM\Column(name="fecha_horas_extras", type="date", nullable=false)
     * @Assert\NotBlank()
     */
    private $fechaHorasExtras;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="horasextras")
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
     * Set cantidadHoras
     *
     * @param string $cantidadHoras
     * @return CHorasExtras
     */
    public function setCantidadHoras($cantidadHoras)
    {
        $this->cantidadHoras = $cantidadHoras;
    
        return $this;
    }

    /**
     * Get cantidadHoras
     *
     * @return string 
     */
    public function getCantidadHoras()
    {
        return $this->cantidadHoras;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return CHorasExtras
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
     * Set fechaHorasExtras
     *
     * @param \DateTime $fechaHorasExtras
     * @return CHorasExtras
     */
    public function setFechaHorasExtras($fechaHorasExtras)
    {
        $this->fechaHorasExtras = $fechaHorasExtras;
    
        return $this;
    }

    /**
     * Get fechaHorasExtras
     *
     * @return \DateTime 
     */
    public function getFechaHorasExtras()
    {
        return $this->fechaHorasExtras;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CHorasExtras
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
        $obj->fechaHorasExtras=$this->fechaHorasExtras->format('Y-m-d');

        $obj->empleado=$this->empleado->getId();
        $obj->cantidadHoras=$this->cantidadHoras;
        $obj->motivo=$this->motivo;
        return $obj;
    }

    /**
     * Set planilla
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @return CHorasExtras
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
}