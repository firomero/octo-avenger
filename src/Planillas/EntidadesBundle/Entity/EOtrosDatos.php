<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EOtrosDatos
 *
 * @ORM\Table(name="e_otros_datos")
 * @ORM\Entity
 */
class EOtrosDatos
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
     * @ORM\Column(name="via_conocimiento", type="string", length=64, nullable=true)
     */
    private $viaConocimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="comentario", type="string", length=255, nullable=true)
     */
    private $comentario;

    /**
     * @var boolean
     *
     * @ORM\Column(name="trabajar_por_turnos", type="boolean", nullable=true)
     */
    private $trabajarPorTurnos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="trabajar_horas_extras", type="boolean", nullable=true)
     */
    private $trabajarHorasExtras;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acepta_trabajo_temporal", type="boolean", nullable=true)
     */
    private $aceptaTrabajoTemporal;

    /**
     * @var $personasEmpresa Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EPersonaEmpresa", mappedBy="otrosDatos")
     */
    private $personasEmpresa;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personasEmpresa = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set viaConocimiento
     *
     * @param string $viaConocimiento
     * @return EOtrosDatos
     */
    public function setViaConocimiento($viaConocimiento)
    {
        $this->viaConocimiento = $viaConocimiento;
    
        return $this;
    }

    /**
     * Get viaConocimiento
     *
     * @return string 
     */
    public function getViaConocimiento()
    {
        return $this->viaConocimiento;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     * @return EOtrosDatos
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    
        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set trabajarPorTurnos
     *
     * @param boolean $trabajarPorTurnos
     * @return EOtrosDatos
     */
    public function setTrabajarPorTurnos($trabajarPorTurnos)
    {
        $this->trabajarPorTurnos = $trabajarPorTurnos;
    
        return $this;
    }

    /**
     * Get trabajarPorTurnos
     *
     * @return boolean 
     */
    public function getTrabajarPorTurnos()
    {
        return $this->trabajarPorTurnos;
    }

    /**
     * Set trabajarHorasExtras
     *
     * @param boolean $trabajarHorasExtras
     * @return EOtrosDatos
     */
    public function setTrabajarHorasExtras($trabajarHorasExtras)
    {
        $this->trabajarHorasExtras = $trabajarHorasExtras;
    
        return $this;
    }

    /**
     * Get trabajarHorasExtras
     *
     * @return boolean 
     */
    public function getTrabajarHorasExtras()
    {
        return $this->trabajarHorasExtras;
    }

    /**
     * Set aceptaTrabajoTemporal
     *
     * @param boolean $aceptaTrabajoTemporal
     * @return EOtrosDatos
     */
    public function setAceptaTrabajoTemporal($aceptaTrabajoTemporal)
    {
        $this->aceptaTrabajoTemporal = $aceptaTrabajoTemporal;
    
        return $this;
    }

    /**
     * Get aceptaTrabajoTemporal
     *
     * @return boolean 
     */
    public function getAceptaTrabajoTemporal()
    {
        return $this->aceptaTrabajoTemporal;
    }

    /**
     * Add personasEmpresa
     *
     * @param \Planillas\EntidadesBundle\Entity\EPersonaEmpresa $personasEmpresa
     * @return EOtrosDatos
     */
    public function addPersonasEmpresa(\Planillas\EntidadesBundle\Entity\EPersonaEmpresa $personasEmpresa)
    {
        $this->personasEmpresa[] = $personasEmpresa;
    
        return $this;
    }

    /**
     * Remove personasEmpresa
     *
     * @param \Planillas\EntidadesBundle\Entity\EPersonaEmpresa $personasEmpresa
     */
    public function removePersonasEmpresa(\Planillas\EntidadesBundle\Entity\EPersonaEmpresa $personasEmpresa)
    {
        $this->personasEmpresa->removeElement($personasEmpresa);
    }

    /**
     * Get personasEmpresa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonasEmpresa()
    {
        return $this->personasEmpresa;
    }
}