<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Planillas\CoreBundle\Helper\DocumentModel;

/**
 * CTramitePortacionArma
 *
 * @ORM\Table(name="c_tramite_portacion_arma")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CTramitePortacionArma extends DocumentModel
{
    /**
     * @var string
     *
     * @ORM\Column(name="tipoTramite", type="string", length=255)
     */
    private $tipoTramite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     * @Assert\Date
     * @Assert\NotBlank
     */
    private $fecha;

    /**
     * @var  \Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma", inversedBy="tramites")
     */
    private $empleadoTramitePortacionArma;

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

    /**
     * Set tipoTramite
     *
     * @param string $tipoTramite
     * @return CTramitePortacionArma
     */
    public function setTipoTramite($tipoTramite)
    {
        $this->tipoTramite = $tipoTramite;
    
        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return string 
     */
    public function getTipoTramite()
    {
        return $this->tipoTramite;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return CTramitePortacionArma
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
     * Set empleadoTramitePortacionArma
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma $empleadoTramitePortacionArma
     * @return CTramitePortacionArma
     */
    public function setEmpleadoTramitePortacionArma(\Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma $empleadoTramitePortacionArma = null)
    {
        $this->empleadoTramitePortacionArma = $empleadoTramitePortacionArma;
    
        return $this;
    }

    /**
     * Get empleadoTramitePortacionArma
     *
     * @return \Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma 
     */
    public function getEmpleadoTramitePortacionArma()
    {
        return $this->empleadoTramitePortacionArma;
    }
}