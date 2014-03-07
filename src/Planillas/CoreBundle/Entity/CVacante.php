<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CVacante
 *
 * @ORM\Table(name="c_vacante")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CVacanteRepository")
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
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTrabajo")
     * @Assert\NotBlank()
     */
    private $trabajo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=64, nullable=false)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_plazas", type="integer", nullable=false)
     * @Assert\NotBlank()
     */
    private $cantidadPlazas;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
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
     * @param \Planillas\NomencladorBundle\Entity\NTrabajo $trabajo
     * @return CVacante
     */
    public function setTrabajo(\Planillas\NomencladorBundle\Entity\NTrabajo $trabajo = null)
    {
        $this->trabajo = $trabajo;
    
        return $this;
    }

    /**
     * Get trabajo
     *
     * @return \Planillas\NomencladorBundle\Entity\NTrabajo
     */
    public function getTrabajo()
    {
        return $this->trabajo;
    }

    /**
     * funcion toString para retornar el objeto en una linea string
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }
}