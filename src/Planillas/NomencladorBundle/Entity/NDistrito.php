<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NDistrito
 *
 * @ORM\Table(name="n_distrito")
 * @ORM\Entity
 */
class NDistrito
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
     * @ORM\Column(name="nombre", type="string", length=32, nullable=true)
     */
    private $nombre;

    /**
     * @var $provincia NProvincia
     *
     * @ORM\ManyToOne(targetEntity="NProvincia", inversedBy="distritos")
     */
    private $provincia;

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
     * @return NDistrito
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
     * Set provincia
     *
     * @param \Planillas\NomencladorBundle\Entity\NProvincia $provincia
     * @return NDistrito
     */
    public function setProvincia(\Planillas\NomencladorBundle\Entity\NProvincia $provincia = null)
    {
        $this->provincia = $provincia;
    
        return $this;
    }

    /**
     * Get provincia
     *
     * @return \Planillas\NomencladorBundle\Entity\NProvincia 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
}