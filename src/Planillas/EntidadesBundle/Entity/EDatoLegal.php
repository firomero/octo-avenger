<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDatoLegal
 *
 * @ORM\Table(name="e_dato_legal")
 * @ORM\Entity
 */
class EDatoLegal
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
     * @ORM\Column(name="fecha_visita_domiciliaria", type="date", nullable=true)
     */
    private $fechaVisitaDomiciliaria;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

    /**
     * @var $antecedentesPenales Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EAntecedentePenal", mappedBy="datoLegal")
     */
    private $antecedentesPenales;

    /**
     * @var $licencias Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ELicencia", mappedBy="datoLegal")
     */
    private $licencias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->antecedentesPenales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->licencias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaVisitaDomiciliaria
     *
     * @param  \DateTime  $fechaVisitaDomiciliaria
     * @return EDatoLegal
     */
    public function setFechaVisitaDomiciliaria($fechaVisitaDomiciliaria)
    {
        $this->fechaVisitaDomiciliaria = $fechaVisitaDomiciliaria;

        return $this;
    }

    /**
     * Get fechaVisitaDomiciliaria
     *
     * @return \DateTime
     */
    public function getFechaVisitaDomiciliaria()
    {
        return $this->fechaVisitaDomiciliaria;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EDatoLegal
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
     * Add antecedentesPenales
     *
     * @param  \Planillas\EntidadesBundle\Entity\EAntecedentePenal $antecedentesPenales
     * @return EDatoLegal
     */
    public function addAntecedentesPenale(\Planillas\EntidadesBundle\Entity\EAntecedentePenal $antecedentesPenales)
    {
        $this->antecedentesPenales[] = $antecedentesPenales;

        return $this;
    }

    /**
     * Remove antecedentesPenales
     *
     * @param \Planillas\EntidadesBundle\Entity\EAntecedentePenal $antecedentesPenales
     */
    public function removeAntecedentesPenale(\Planillas\EntidadesBundle\Entity\EAntecedentePenal $antecedentesPenales)
    {
        $this->antecedentesPenales->removeElement($antecedentesPenales);
    }

    /**
     * Get antecedentesPenales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAntecedentesPenales()
    {
        return $this->antecedentesPenales;
    }

    /**
     * Add licencias
     *
     * @param  \Planillas\EntidadesBundle\Entity\ELicencia $licencias
     * @return EDatoLegal
     */
    public function addLicencia(\Planillas\EntidadesBundle\Entity\ELicencia $licencias)
    {
        $this->licencias[] = $licencias;

        return $this;
    }

    /**
     * Remove licencias
     *
     * @param \Planillas\EntidadesBundle\Entity\ELicencia $licencias
     */
    public function removeLicencia(\Planillas\EntidadesBundle\Entity\ELicencia $licencias)
    {
        $this->licencias->removeElement($licencias);
    }

    /**
     * Get licencias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicencias()
    {
        return $this->licencias;
    }

    public function __toString()
    {
        return $this->id.'---';
    }
}
