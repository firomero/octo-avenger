<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CHorario
 *
 * @ORM\Table(name="c_horario")
 * @ORM\Entity
 */
class CHorario
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
     * @var $empleado CEmpleado
     *
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="horario")
     */
    private $empleado;

    /**
     * @var $horarioDias Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CHorarioDias", mappedBy="horario", cascade={"all"})
     */
    private $horarioDias;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horarioDias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add horarioDias
     *
     * @param \Planillas\CoreBundle\Entity\CHorarioDias $horarioDias
     * @return CHorario
     */
    public function addHorarioDia(\Planillas\CoreBundle\Entity\CHorarioDias $horarioDias)
    {
        $horarioDias->setHorario($this);
        $this->horarioDias[] = $horarioDias;
        return $this;
    }

    /**
     * Remove horarioDias
     *
     * @param \Planillas\CoreBundle\Entity\CHorarioDias $horarioDias
     */
    public function removeHorarioDia(\Planillas\CoreBundle\Entity\CHorarioDias $horarioDias)
    {
        $this->horarioDias->removeElement($horarioDias);
    }

    /**
     * Get horarioDias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHorarioDias()
    {
        return $this->horarioDias;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CHorario
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
}