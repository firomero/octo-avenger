<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EPersonaEmpresa
 *
 * @ORM\Table(name="e_persona_empresa")
 * @ORM\Entity
 */
class EPersonaEmpresa
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
     * @var $otrosDatos EOtrosDatos
     *
     * @ORM\ManyToOne(targetEntity="EOtrosDatos", inversedBy="personasEmpresa")
     */
    private $otrosDatos;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=32, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=64, nullable=true)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_relacion", type="string", length=20, nullable=true)
     */
    private $tipoRelacion;

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
     * Set nombre
     *
     * @param  string          $nombre
     * @return EPersonaEmpresa
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param  string          $apellidos
     * @return EPersonaEmpresa
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set tipoRelacion
     *
     * @param  string          $tipoRelacion
     * @return EPersonaEmpresa
     */
    public function setTipoRelacion($tipoRelacion)
    {
        $this->tipoRelacion = $tipoRelacion;

        return $this;
    }

    /**
     * Get tipoRelacion
     *
     * @return string
     */
    public function getTipoRelacion()
    {
        return $this->tipoRelacion;
    }

    /**
     * Set otrosDatos
     *
     * @param  \Planillas\EntidadesBundle\Entity\EOtrosDatos $otrosDatos
     * @return EPersonaEmpresa
     */
    public function setOtrosDatos(\Planillas\EntidadesBundle\Entity\EOtrosDatos $otrosDatos = null)
    {
        $this->otrosDatos = $otrosDatos;

        return $this;
    }

    /**
     * Get otrosDatos
     *
     * @return \Planillas\EntidadesBundle\Entity\EOtrosDatos
     */
    public function getOtrosDatos()
    {
        return $this->otrosDatos;
    }
}
