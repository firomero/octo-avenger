<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NDeportes
 *
 * @ORM\Table(name="n_deportes")
 * @ORM\Entity
 */
class NDeportes
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
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;


    /**
     * @var $deportes Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Planillas\EntidadesBundle\Entity\EHistoriaSalud",mappedBy="deportes")
     */
    private $historias_salud;
    /**
     * Get deporteId
     *
     * @return integer 
     */
    public function getHistoriasSalud()
    {
        return $this->historias_salud;
    }
    
    public function setHistoriasSalud(\Planillas\EntidadesBundle\Entity\EHistoriaSalud $historia_salud)
    {
        return $this->historias_salud->add($historia_salud);
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return NDeportes
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->historias_salud = new \Doctrine\Common\Collections\ArrayCollection();
    }
}