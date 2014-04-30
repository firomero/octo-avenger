<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NBonificacionPuesto
 *
 * @ORM\Table(name="n_bonificacion_puesto")
 * @ORM\Entity
 */
class NBonificacionPuesto
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
     * @param  string              $nombre
     * @return NBonificacionPuesto
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
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }

}
