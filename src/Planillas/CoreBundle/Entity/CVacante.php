<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CVacante
 *
 * @ORM\Table(name="c_vacante")
 * @ORM\Entity
 */
class CVacante
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
     * @var $trabajo NTrabajo
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTrabajo")
     */
    private $trabajo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=64, nullable=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_plaas", type="integer", nullable=true)
     */
    private $cantidadPlazas;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;


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
     * Set nombre
     *
     * @param string $nombre
     * @return CVacante
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
     * Set cantidadPlazas
     *
     * @param integer $cantidadPlazas
     * @return CVacante
     */
    public function setCantidadPlazas($cantidadPlazas)
    {
        $this->cantidadPlazas = $cantidadPlazas;
    
        return $this;
    }

    /**
     * Get cantidadPlazas
     *
     * @return integer 
     */
    public function getCantidadPlazas()
    {
        return $this->cantidadPlazas;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CVacante
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
     * Set activo
     *
     * @param boolean $activo
     * @return CVacante
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set trabajo
     *
     * @param \Planillas\CoreBundle\Entity\CTrabajo $trabajo
     * @return CVacante
     */
    public function setTrabajo(\Planillas\CoreBundle\Entity\CTrabajo $trabajo = null)
    {
        $this->trabajo = $trabajo;
    
        return $this;
    }

    /**
     * Get trabajo
     *
     * @return \Planillas\CoreBundle\Entity\CTrabajo 
     */
    public function getTrabajo()
    {
        return $this->trabajo;
    }
}