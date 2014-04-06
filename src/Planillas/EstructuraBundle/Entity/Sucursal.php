<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sucursal
 *
 * @ORM\Table(name="e_estructura_sucursal")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\SucursalRepository")
 */
class Sucursal implements EntityEstructuraInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="sucursales")
     */
    private $cliente;

    /**
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="Empresa")
     */
    private $empresa;

    /**
     * @var Turno
     *
     * @ORM\OneToMany(targetEntity="Turno", mappedBy="sucursal", cascade={"persist","remove"})
     */
    private $turnos;

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
     * @param  string   $nombre
     * @return Sucursal
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
     * Constructor
     */
    public function __construct()
    {
        $this->turnos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set cliente
     *
     * @param \Planillas\EstructuraBundle\Entity\Cliente $cliente
     * @return Sucursal
     */
    public function setCliente(\Planillas\EstructuraBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Planillas\EstructuraBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set empresa
     *
     * @param \Planillas\EstructuraBundle\Entity\Empresa $empresa
     * @return Sucursal
     */
    public function setEmpresa(\Planillas\EstructuraBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \Planillas\EstructuraBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Add turnos
     *
     * @param \Planillas\EstructuraBundle\Entity\Turno $turnos
     * @return Sucursal
     */
    public function addTurno(\Planillas\EstructuraBundle\Entity\Turno $turnos)
    {
        $this->turnos[] = $turnos;
    
        return $this;
    }

    /**
     * Remove turnos
     *
     * @param \Planillas\EstructuraBundle\Entity\Turno $turnos
     */
    public function removeTurno(\Planillas\EstructuraBundle\Entity\Turno $turnos)
    {
        $this->turnos->removeElement($turnos);
    }

    /**
     * Get turnos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTurnos()
    {
        return $this->turnos;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }
}