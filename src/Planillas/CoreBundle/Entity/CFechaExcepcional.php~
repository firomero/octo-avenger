<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CFechaExcepcional
 *
 * @ORM\Table(name="c_fecha_excepcional")
 * @ORM\Entity
 */
class CFechaExcepcional
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
     * @var $horario CHorario
     *
     * @ORM\ManyToOne(targetEntity="CHorario")
     */
    private $horario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return CFechaExcepcional
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set horario
     *
     * @param \Planillas\CoreBundle\Entity\CHorario $horario
     * @return CFechaExcepcional
     */
    public function setHorario(\Planillas\CoreBundle\Entity\CHorario $horario = null)
    {
        $this->horario = $horario;
    
        return $this;
    }

    /**
     * Get horario
     *
     * @return \Planillas\CoreBundle\Entity\CHorario 
     */
    public function getHorario()
    {
        return $this->horario;
    }
}