<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CHorarioDias
 *
 * @ORM\Table(name="c_horario_dias")
 * @ORM\Entity
 */
class CHorarioDias
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
     * @ORM\ManyToOne(targetEntity="CHorario", inversedBy="horarioDias")
     */
    private $horario;

    /**
     * @var string
     *
     * @ORM\Column(name="dia", type="string", length=32, nullable=true)
     */
    private $dia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_inicio", type="time", nullable=true)
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_fin", type="time", nullable=true)
     */
    private $horaFin;


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
     * Set dia
     *
     * @param string $dia
     * @return CHorarioDias
     */
    public function setDia($dia)
    {
        $this->dia = $dia;
    
        return $this;
    }

    /**
     * Get dia
     *
     * @return string 
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     * @return CHorarioDias
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
    
        return $this;
    }

    /**
     * Get horaInicio
     *
     * @return \DateTime 
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set horaFin
     *
     * @param \DateTime $horaFin
     * @return CHorarioDias
     */
    public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;
    
        return $this;
    }

    /**
     * Get horaFin
     *
     * @return \DateTime 
     */
    public function getHoraFin()
    {
        return $this->horaFin;
    }

    /**
     * Set horario
     *
     * @param \Planillas\CoreBundle\Entity\CHorario $horario
     * @return CHorarioDias
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