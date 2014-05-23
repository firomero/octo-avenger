<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Planillas\CoreBundle\Helper\DocumentModel;

/**
 * CEmpleadoRegistroLaboral
 *
 * @ORM\Table(name="c_empleado_registro_laboral")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CEmpleadoRegistroLaboral extends DocumentModel
{
    /**
     * @var  \Planillas\NomencladorBundle\Entity\NTipoRegistroLaboral
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTipoRegistroLaboral")
     *
     */
    private $tipoRegistroLaboral;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var  \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CEmpleadoRegistroLaboral
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
     * Set tipoRegistroLaboral
     *
     * @param \Planillas\NomencladorBundle\Entity\NTipoRegistroLaboral $tipoRegistroLaboral
     * @return CEmpleadoRegistroLaboral
     */
    public function setTipoRegistroLaboral(\Planillas\NomencladorBundle\Entity\NTipoRegistroLaboral $tipoRegistroLaboral = null)
    {
        $this->tipoRegistroLaboral = $tipoRegistroLaboral;
    
        return $this;
    }

    /**
     * Get tipoRegistroLaboral
     *
     * @return \Planillas\NomencladorBundle\Entity\NTipoRegistroLaboral 
     */
    public function getTipoRegistroLaboral()
    {
        return $this->tipoRegistroLaboral;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CEmpleadoRegistroLaboral
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