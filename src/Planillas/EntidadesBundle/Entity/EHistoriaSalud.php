<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EHistoriaSalud
 *
 * @ORM\Table(name="e_historia_salud")
 * @ORM\Entity
 */
class EHistoriaSalud
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
     * @var $tipoEnfermedad Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EHistoriaSaludTipoEnfermedad", mappedBy="historiaSalud")
     */
    private $tipoEnfermedad;

    /**
     * @var string
     *
     * @ORM\Column(name="ultima_enfermedad", type="string", length=64, nullable=true)
     */
    private $ultimaEnfermedad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ultima_enfermedad", type="date", nullable=true)
     */
    private $fechaUltimaEnfermedad;

    /**
     * @var string
     *
     * @ORM\Column(name="fuma", type="boolean", nullable=true)
     */
    private $fuma;

    /**
     * @var string
     *
     * @ORM\Column(name="fuma_frecuencia", type="string", length=32, nullable=true)
     */
    private $fumaFrecuencia;

    /**
     * @var string
     *
     * @ORM\Column(name="bebe", type="boolean", nullable=true)
     */
    private $bebe;

    /**
     * @var string
     *
     * @ORM\Column(name="bebe_frecuencia", type="string", length=32, nullable=true)
     */
    private $bebeFrecuencia;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="historia_salud")
     */
    private $empleado;

    /**
     * @var $juegosAzar Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Planillas\NomencladorBundle\Entity\NJuegoAzar", cascade={"persist"})
     * @ORM\JoinTable(name="e_historia_salud_juego_azar")
     */
    private $juegosAzar;

    /**
     * @var $deportes Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Planillas\NomencladorBundle\Entity\NDeportes", cascade={"persist"})
     * @ORM\JoinTable(name="e_historia_salud_deportes")
     */
    private $deportes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tipoEnfermedad = new \Doctrine\Common\Collections\ArrayCollection();
        $this->juegosAzar = new \Doctrine\Common\Collections\ArrayCollection();
        $this->deportes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ultimaEnfermedad
     *
     * @param  string         $ultimaEnfermedad
     * @return EHistoriaSalud
     */
    public function setUltimaEnfermedad($ultimaEnfermedad)
    {
        $this->ultimaEnfermedad = $ultimaEnfermedad;

        return $this;
    }

    /**
     * Get ultimaEnfermedad
     *
     * @return string
     */
    public function getUltimaEnfermedad()
    {
        return $this->ultimaEnfermedad;
    }

    /**
     * Set fechaUltimaEnfermedad
     *
     * @param  \DateTime      $fechaUltimaEnfermedad
     * @return EHistoriaSalud
     */
    public function setFechaUltimaEnfermedad($fechaUltimaEnfermedad)
    {
        $this->fechaUltimaEnfermedad = $fechaUltimaEnfermedad;

        return $this;
    }

    /**
     * Get fechaUltimaEnfermedad
     *
     * @return \DateTime
     */
    public function getFechaUltimaEnfermedad()
    {
        return $this->fechaUltimaEnfermedad;
    }

    /**
     * Set fuma
     *
     * @param  boolean        $fuma
     * @return EHistoriaSalud
     */
    public function setFuma($fuma)
    {
        $this->fuma = $fuma;

        return $this;
    }

    /**
     * Get fuma
     *
     * @return boolean
     */
    public function getFuma()
    {
        return $this->fuma;
    }

    /**
     * Set fumaFrecuencia
     *
     * @param  string         $fumaFrecuencia
     * @return EHistoriaSalud
     */
    public function setFumaFrecuencia($fumaFrecuencia)
    {
        $this->fumaFrecuencia = $fumaFrecuencia;

        return $this;
    }

    /**
     * Get fumaFrecuencia
     *
     * @return string
     */
    public function getFumaFrecuencia()
    {
        return $this->fumaFrecuencia;
    }

    /**
     * Set bebe
     *
     * @param  boolean        $bebe
     * @return EHistoriaSalud
     */
    public function setBebe($bebe)
    {
        $this->bebe = $bebe;

        return $this;
    }

    /**
     * Get bebe
     *
     * @return boolean
     */
    public function getBebe()
    {
        return $this->bebe;
    }

    /**
     * Set bebeFrecuencia
     *
     * @param  string         $bebeFrecuencia
     * @return EHistoriaSalud
     */
    public function setBebeFrecuencia($bebeFrecuencia)
    {
        $this->bebeFrecuencia = $bebeFrecuencia;

        return $this;
    }

    /**
     * Get bebeFrecuencia
     *
     * @return string
     */
    public function getBebeFrecuencia()
    {
        return $this->bebeFrecuencia;
    }

    /**
     * Add tipoEnfermedad
     *
     * @param  \Planillas\EntidadesBundle\Entity\EHistoriaSaludTipoEnfermedad $tipoEnfermedad
     * @return EHistoriaSalud
     */
    public function addTipoEnfermedad(\Planillas\EntidadesBundle\Entity\EHistoriaSaludTipoEnfermedad $tipoEnfermedad)
    {
        $this->tipoEnfermedad[] = $tipoEnfermedad;

        return $this;
    }

    /**
     * Remove tipoEnfermedad
     *
     * @param \Planillas\EntidadesBundle\Entity\EHistoriaSaludTipoEnfermedad $tipoEnfermedad
     */
    public function removeTipoEnfermedad(\Planillas\EntidadesBundle\Entity\EHistoriaSaludTipoEnfermedad $tipoEnfermedad)
    {
        $this->tipoEnfermedad->removeElement($tipoEnfermedad);
    }

    /**
     * Get tipoEnfermedad
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoEnfermedad()
    {
        return $this->tipoEnfermedad;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EHistoriaSalud
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

    /**
     * Add juegosAzar
     *
     * @param  \Planillas\NomencladorBundle\Entity\NJuegoAzar $juegosAzar
     * @return EHistoriaSalud
     */
    public function addJuegosAzar(\Planillas\NomencladorBundle\Entity\NJuegoAzar $juegosAzar)
    {
        $this->juegosAzar->add($juegosAzar);

        return $this;
    }

    /**
     * Remove juegosAzar
     *
     * @param \Planillas\NomencladorBundle\Entity\NJuegoAzar $juegosAzar
     */
    public function removeJuegosAzar(\Planillas\NomencladorBundle\Entity\NJuegoAzar $juegosAzar)
    {
        $this->juegosAzar->removeElement($juegosAzar);
    }

    /**
     * Get juegosAzar
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJuegosAzar()
    {
        return $this->juegosAzar;
    }

    /**
     * Add deportes
     *
     * @param  \Planillas\NomencladorBundle\Entity\NDeportes $deporte
     * @return EHistoriaSalud
     */
    public function addDeporte(\Planillas\NomencladorBundle\Entity\NDeportes $deporte)
    {
        //$deporte->setHistoriasSalud($this);
        $this->deportes->add($deporte);

        return $this;
    }

    /**
     * Remove deportes
     *
     * @param \Planillas\NomencladorBundle\Entity\NDeportes $deportes
     */
    public function removeDeporte(\Planillas\NomencladorBundle\Entity\NDeportes $deportes)
    {
        $this->deportes->removeElement($deportes);
    }

    /**
     * Get deportes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeportes()
    {
        return $this->deportes;
    }
}
