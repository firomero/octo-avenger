<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CAusencias
 *
 * @ORM\Table(name="c_planillas")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CPlanillasRepository")
 */
class CPlanillas
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     */
    private $fechaFin;
    
   /**
     * @var $periodo Planillas/NomencladorBundle/Entity/NPeriodoPago
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NPeriodoPago")
     */
    private $periodo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date", nullable=true)
     */
    private $created_at;
    


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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return CPlanillas
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return CPlanillas
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return CPlanillas
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set periodo
     *
     * @param \Planillas\NomencladorBundle\Entity\NPeriodoPago $periodo
     * @return CPlanillas
     */
    public function setPeriodo(\Planillas\NomencladorBundle\Entity\NPeriodoPago $periodo = null)
    {
        $this->periodo = $periodo;
    
        return $this;
    }

    /**
     * Get periodo
     *
     * @return \Planillas\NomencladorBundle\Entity\NPeriodoPago 
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }
}