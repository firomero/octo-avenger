<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NProvincia
 *
 * @ORM\Table(name="n_provincia")
 * @ORM\Entity
 */
class NProvincia
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
     * @var $distritos Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="NDistrito", mappedBy="provincia")
     */
    private $distritos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->distritos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param  string     $nombre
     * @return NProvincia
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
     * Add distritos
     *
     * @param  \Planillas\NomencladorBundle\Entity\NDistrito $distritos
     * @return NProvincia
     */
    public function addDistrito(\Planillas\NomencladorBundle\Entity\NDistrito $distritos)
    {
        $this->distritos[] = $distritos;

        return $this;
    }

    /**
     * Remove distritos
     *
     * @param \Planillas\NomencladorBundle\Entity\NDistrito $distritos
     */
    public function removeDistrito(\Planillas\NomencladorBundle\Entity\NDistrito $distritos)
    {
        $this->distritos->removeElement($distritos);
    }

    /**
     * Get distritos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistritos()
    {
        return $this->distritos;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
