<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NHorasExtras
 *
 * @ORM\Table(name="n_horas_extras")
 * @ORM\Entity()
 */
class NHorasExtras
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidadHoras", type="integer")
     */
    private $cantidadHoras;

    /**
     * @var float
     *
     * @ORM\Column(name="factor", type="decimal")
     */
    private $factor;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;


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
     * @param integer $cantidadHoras
     * @return NHorasExtras
     */
    public function setCantidadHoras($cantidadHoras)
    {
        $this->cantidadHoras = $cantidadHoras;
    
        return $this;
    }

    /**
     * Get cantidadHoras
     *
     * @return integer 
     */
    public function getCantidadHoras()
    {
        return $this->cantidadHoras;
    }

    /**
     * Set factor
     *
     * @param float $factor
     * @return NHorasExtras
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;
    
        return $this;
    }

    /**
     * Get factor
     *
     * @return float 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return NHorasExtras
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
}
