<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EHistoriaSaludTipoEnfermedad
 *
 * @ORM\Table(name="e_historia_salud_tipo_enfermedad")
 * @ORM\Entity
 */
class EHistoriaSaludTipoEnfermedad
{

    /**
     * @var $historiaSalud EHistoriaSalud
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EHistoriaSalud", inversedBy="tiposEnfermedad")
     */
    private $historiaSalud;

    /**
     * @var $tipoEnfermedad Planillas/NomencladorBundle/Entity/NTipoEnfermedad
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTipoEnfermedad")
     */
    private $tipoEnfermedad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * Set fecha
     *
     * @param  \DateTime                    $fecha
     * @return EHistoriaSaludTipoEnfermedad
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
     * Set historiaSalud
     *
     * @param  \Planillas\EntidadesBundle\Entity\EHistoriaSalud $historiaSalud
     * @return EHistoriaSaludTipoEnfermedad
     */
    public function setHistoriaSalud(\Planillas\EntidadesBundle\Entity\EHistoriaSalud $historiaSalud)
    {
        $this->historiaSalud = $historiaSalud;

        return $this;
    }

    /**
     * Get historiaSalud
     *
     * @return \Planillas\EntidadesBundle\Entity\EHistoriaSalud
     */
    public function getHistoriaSalud()
    {
        return $this->historiaSalud;
    }

    /**
     * Set tipoEnfermedad
     *
     * @param  \Planillas\NomencladorBundle\Entity\NTipoEnfermedad $tipoEnfermedad
     * @return EHistoriaSaludTipoEnfermedad
     */
    public function setTipoEnfermedad(\Planillas\NomencladorBundle\Entity\NTipoEnfermedad $tipoEnfermedad)
    {
        $this->tipoEnfermedad = $tipoEnfermedad;

        return $this;
    }

    /**
     * Get tipoEnfermedad
     *
     * @return \Planillas\NomencladorBundle\Entity\NTipoEnfermedad
     */
    public function getTipoEnfermedad()
    {
        return $this->tipoEnfermedad;
    }
}
