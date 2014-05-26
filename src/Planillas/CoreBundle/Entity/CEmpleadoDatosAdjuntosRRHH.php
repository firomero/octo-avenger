<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Planillas\CoreBundle\Helper\DocumentModel;

/**
 * CEmpleadoDatosAdjuntosRRHH
 *
 * @ORM\Table(name="c_empleado_datos_adjuntos_rrhh")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CEmpleadoDatosAdjuntosRRHH extends DocumentModel
{
    /**
     * @var  \Planillas\NomencladorBundle\Entity\NTipoDatoAdjuntoRRHH
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTipoDatoAdjuntoRRHH")
     * @Assert\NotBlank
     */
    private $tipoDatoAdjunto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @var  \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

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
     * Set tipoDatoAdjunto
     *
     * @param string $tipoDatoAdjunto
     * @return CEmpleadoDatosAdjuntosRRHH
     */
    public function setTipoDatoAdjunto($tipoDatoAdjunto)
    {
        $this->tipoDatoAdjunto = $tipoDatoAdjunto;
    
        return $this;
    }

    /**
     * Get tipoDatoAdjunto
     *
     * @return string 
     */
    public function getTipoDatoAdjunto()
    {
        return $this->tipoDatoAdjunto;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return CEmpleadoDatosAdjuntosRRHH
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return CEmpleadoDatosAdjuntosRRHH
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CEmpleadoOtrasAnotaciones
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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        parent::__preUpload();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        parent::__upload();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        parent::__removeUpload();
    }
}
