<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CEmpleadoReferenciaPersonal
 *
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CEmpleadoReferenciaPersonalRepository")
 */
class CEmpleadoReferenciaPersonal extends CEmpleadoReferencias
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombrePersona", type="string", length=255)
     */
    private $nombrePersona;

    /**
     * @var string
     *
     * @ORM\Column(name="tiempoConocerlo", type="string", length=255)
     */
    private $tiempoConocerlo;

    /**
     * @var \Planillas\NomencladorBundle\Entity\NEstadoCivil
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NEstadoCivil")
     */
    private $estadoCivil;

    /**
     * @var boolean
     *
     * @ORM\Column(name="poseeHijos", type="boolean")
     */
    private $poseeHijos;

    /**
     * @var string
     *
     * @ORM\Column(name="lugarResidencia", type="string", length=255)
     */
    private $lugarResidencia;

    /**
     * @var string
     *
     * @ORM\Column(name="conocePQDejoLaborar", type="string", length=255)
     */
    private $conocePQDejoLaborar;

    /**
     * @param string $conocePQDejoLaborar
     */
    public function setConocePQDejoLaborar($conocePQDejoLaborar)
    {
        $this->conocePQDejoLaborar = $conocePQDejoLaborar;
    }

    /**
     * @return string
     */
    public function getConocePQDejoLaborar()
    {
        return $this->conocePQDejoLaborar;
    }

    /**
     * @param \Planillas\NomencladorBundle\Entity\NEstadoCivil $estadoCivil
     */
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;
    }

    /**
     * @return \Planillas\NomencladorBundle\Entity\NEstadoCivil
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * @param string $lugarResidencia
     */
    public function setLugarResidencia($lugarResidencia)
    {
        $this->lugarResidencia = $lugarResidencia;
    }

    /**
     * @return string
     */
    public function getLugarResidencia()
    {
        return $this->lugarResidencia;
    }

    /**
     * @param string $nombrePersona
     */
    public function setNombrePersona($nombrePersona)
    {
        $this->nombrePersona = $nombrePersona;
    }

    /**
     * @return string
     */
    public function getNombrePersona()
    {
        return $this->nombrePersona;
    }

    /**
     * @param boolean $poseeHijos
     */
    public function setPoseeHijos($poseeHijos)
    {
        $this->poseeHijos = $poseeHijos;
    }

    /**
     * @return boolean
     */
    public function getPoseeHijos()
    {
        return $this->poseeHijos;
    }

    /**
     * @param string $tiempoConocerlo
     */
    public function setTiempoConocerlo($tiempoConocerlo)
    {
        $this->tiempoConocerlo = $tiempoConocerlo;
    }

    /**
     * @return string
     */
    public function getTiempoConocerlo()
    {
        return $this->tiempoConocerlo;
    }


}
