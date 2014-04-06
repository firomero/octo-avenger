<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Turno
 *
 * @ORM\Table(name="e_estructura_turno")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\TurnoRepository")
 */
class Turno implements EntityEstructuraInterface
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
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal", inversedBy="turnos")
     * @Assert\NotBlank()
     */
    private $sucursal;

    /**
     * @var Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @Assert\NotBlank()
     */
    private $cliente;

    /**
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @Assert\NotBlank()
     */
    private $empresa;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Puesto", mappedBy="turno", cascade={"persist","remove"})
     */
    private $puestos;

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
     * @param  string $nombre
     * @return Turno
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
        $this->puestos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set sucursal
     *
     * @param  \Planillas\EstructuraBundle\Entity\Sucursal $sucursal
     * @return Turno
     */
    public function setSucursal(\Planillas\EstructuraBundle\Entity\Sucursal $sucursal = null)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return \Planillas\EstructuraBundle\Entity\Sucursal
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * Set cliente
     *
     * @param  \Planillas\EstructuraBundle\Entity\Cliente $cliente
     * @return Turno
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
     * @param  \Planillas\EstructuraBundle\Entity\Empresa $empresa
     * @return Turno
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
     * Add puestos
     *
     * @param  \Planillas\EstructuraBundle\Entity\Puesto $puestos
     * @return Turno
     */
    public function addPuesto(\Planillas\EstructuraBundle\Entity\Puesto $puestos)
    {
        $this->puestos[] = $puestos;

        return $this;
    }

    /**
     * Remove puestos
     *
     * @param \Planillas\EstructuraBundle\Entity\Puesto $puestos
     */
    public function removePuesto(\Planillas\EstructuraBundle\Entity\Puesto $puestos)
    {
        $this->puestos->removeElement($puestos);
    }

    /**
     * Get puestos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestos()
    {
        return $this->puestos;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }
}
