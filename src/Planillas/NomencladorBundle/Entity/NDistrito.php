<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="nombre", type="string", length=64)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var $provincia NProvincia
     *
     * @ORM\ManyToOne(targetEntity="NProvincia", inversedBy="distritos")
     * @Assert\NotBlank()
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
     * @param  string    $nombre
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
     * @param  \Planillas\NomencladorBundle\Entity\NProvincia $provincia
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

    public function __toString()
    {
        return $this->nombre;
    }
}
