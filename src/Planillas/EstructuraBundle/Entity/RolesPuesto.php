<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RolesPuesto
 *
 * @ORM\Table(name="e_estructura_roles_puesto")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\RolesPuestoRepository")
 */
class RolesPuesto
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
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @Assert\NotBlank()
     */
    private $empresa;

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
     * @var Turno
     *
     * @ORM\ManyToOne(targetEntity="Turno", inversedBy="puestos")
     * @Assert\NotBlank()
     */
    private $turno;

    /**
     * @var  \Planillas\EstructuraBundle\Entity\Puesto $puesto
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Puesto", inversedBy="roles")
     */
    private $puesto;

    /**
     * @var  \Planillas\CoreBundle\Entity\CHorario $rol
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CHorario")
     */
    private $rol;


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
     * Set puesto
     *
     * @param \Planillas\EstructuraBundle\Entity\Puesto $puesto
     * @return RolesPuesto
     */
    public function setPuesto(\Planillas\EstructuraBundle\Entity\Puesto $puesto = null)
    {
        $this->puesto = $puesto;
    
        return $this;
    }

    /**
     * Get puesto
     *
     * @return \Planillas\EstructuraBundle\Entity\Puesto 
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * Set rol
     *
     * @param \Planillas\CoreBundle\Entity\CHorario $rol
     * @return RolesPuesto
     */
    public function setRol(\Planillas\CoreBundle\Entity\CHorario $rol = null)
    {
        $this->rol = $rol;
    
        return $this;
    }

    /**
     * Get rol
     *
     * @return \Planillas\CoreBundle\Entity\CHorario 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set empresa
     *
     * @param \Planillas\EstructuraBundle\Entity\Empresa $empresa
     * @return RolesPuesto
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
     * Set sucursal
     *
     * @param \Planillas\EstructuraBundle\Entity\Sucursal $sucursal
     * @return RolesPuesto
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
     * @param \Planillas\EstructuraBundle\Entity\Cliente $cliente
     * @return RolesPuesto
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
     * Set turno
     *
     * @param \Planillas\EstructuraBundle\Entity\Turno $turno
     * @return RolesPuesto
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

    public function __toString()
    {
        return $this->rol->getTitulo();
    }
}