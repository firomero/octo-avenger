<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CHorario
 *
 * @ORM\Table(name="c_horario")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CHorarioRepository")
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
     * @var $empleado Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", mappedBy="horario")
     */
    private $empleado;
    /**
     * @var $fechaexcepcional CFechaExcepcional
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CFechaExcepcional", mappedBy="fechaexcepcional")
     */
    private $fechaexcepcional;
     /**
     * @var string
     * @ORM\Column(name="titulo", type="string", length=100, nullable=true)
     */
    private $titulo;
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
        $this->empleado = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fechaexcepcional = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titulo
     *
     * @param string $titulo
     * @return CHorario
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Add empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CHorario
     */
    public function addEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado)
    {
        $this->empleado[] = $empleado;
    
        return $this;
    }

    /**
     * Remove empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     */
    public function removeEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado)
    {
        $this->empleado->removeElement($empleado);
    }

    /**
     * Get empleado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Add fechaexcepcional
     *
     * @param \Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional
     * @return CHorario
     */
    public function addFechaexcepcional(\Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional)
    {
        $this->fechaexcepcional[] = $fechaexcepcional;
    
        return $this;
    }

    /**
     * Remove fechaexcepcional
     *
     * @param \Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional
     */
    public function removeFechaexcepcional(\Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional)
    {
        $this->fechaexcepcional->removeElement($fechaexcepcional);
    }

    /**
     * Get fechaexcepcional
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFechaexcepcional()
    {
        return $this->fechaexcepcional;
    }

    /**
     * Add horarioDias
     *
     * @param \Planillas\CoreBundle\Entity\CHorarioDias $horarioDias
     * @return CHorario
     */
    public function addHorarioDia(\Planillas\CoreBundle\Entity\CHorarioDias $horarioDias)
    {
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
}