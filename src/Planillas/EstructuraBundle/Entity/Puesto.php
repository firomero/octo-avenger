<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Planillas\CoreBundle\Entity\CHorario;
use Planillas\NomencladorBundle\Entity\NBonificacionPuesto;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Puesto
 *
 * @ORM\Table(name="e_estructura_puesto")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\PuestoRepository")
 */
class Puesto implements EntityEstructuraInterface
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
     * @var float
     *
     * @ORM\Column(name="salario", type="float")
     * @Assert\NotBlank()
     */
    private $salario;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto", mappedBy="puesto", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $bonificaciones;

    /**
     * @var Turno
     *
     * @ORM\ManyToOne(targetEntity="Turno", inversedBy="puestos")
     * @Assert\NotBlank()
     */
    private $turno;

    /**
     * @var Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
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
     * @var  \Doctrine\Common\Collections\ArrayCollection $roles
     *
     * @ORM\OneToMany(targetEntity="Planillas\EstructuraBundle\Entity\RolesPuesto", mappedBy="puesto", cascade={"persist","remove"})
     */
    private $roles;

    /**
     * @var  \Planillas\NomencladorBundle\Entity\NHorasExtras
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NHorasExtras")
     * @Assert\NotBlank()
     */
    private $tipoHoraExtra;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bonificaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param  string $nombre
     * @return Puesto
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
     * Set salario
     *
     * @param  float  $salario
     * @return Puesto
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return float
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set turno
     *
     * @param  \Planillas\EstructuraBundle\Entity\Turno $turno
     * @return Puesto
     */
    public function setTurno(\Planillas\EstructuraBundle\Entity\Turno $turno = null)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return \Planillas\EstructuraBundle\Entity\Turno
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set sucursal
     *
     * @param  \Planillas\EstructuraBundle\Entity\Sucursal $sucursal
     * @return Puesto
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
     * @return Puesto
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
     * @return Puesto
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
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Add bonificaciones
     *
     * @param \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto $bonificaciones
     * @return Puesto
     */
    public function addBonificacione(\Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto $bonificaciones)
    {
        $bonificaciones->setPuesto($this);

        $this->bonificaciones->add($bonificaciones);
    
        return $this;
    }

    /**
     * Remove bonificaciones
     *
     * @param \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto $bonificaciones
     */
    public function removeBonificacione(\Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto $bonificaciones)
    {
        $this->bonificaciones->removeElement($bonificaciones);
    }

    /**
     * Get bonificaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBonificaciones()
    {
        return $this->bonificaciones;
    }

    /**
     * Add roles
     *
     * @param \Planillas\EstructuraBundle\Entity\RolesPuesto $roles
     * @return Puesto
     */
    public function addRole(\Planillas\EstructuraBundle\Entity\RolesPuesto $roles)
    {
        $roles->setPuesto($this);

        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Planillas\EstructuraBundle\Entity\RolesPuesto $roles
     */
    public function removeRole(\Planillas\EstructuraBundle\Entity\RolesPuesto $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set tipoHoraExtra
     *
     * @param \Planillas\NomencladorBundle\Entity\NHorasExtras $tipoHoraExtra
     * @return Puesto
     */
    public function setTipoHoraExtra(\Planillas\NomencladorBundle\Entity\NHorasExtras $tipoHoraExtra = null)
    {
        $this->tipoHoraExtra = $tipoHoraExtra;
    
        return $this;
    }

    /**
     * Get tipoHoraExtra
     *
     * @return \Planillas\NomencladorBundle\Entity\NHorasExtras 
     */
    public function getTipoHoraExtra()
    {
        return $this->tipoHoraExtra;
    }
}