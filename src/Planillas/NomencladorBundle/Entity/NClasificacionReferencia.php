<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NClasificacionReferencia
 *
 * @ORM\Table(name="n_clasificacion_referencia")
 * @ORM\Entity
 */
class NClasificacionReferencia
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
     * Set nombre
     *
     * @param string $nombre
     * @return NClasificacionReferencia
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

    function __toString()
    {
        return $this->nombre;
    }


}