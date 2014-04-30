<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CPuestoEmpleado
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CPuestoEmpleadoRepository")
 * @ORM\Table(name="c_puesto_empleado")
 */
class CPuestoEmpleado
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
     * @var \Planillas\EstructuraBundle\Entity\Empresa
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Empresa")
     */
    private $empresa;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Cliente")
     */
    private $cliente;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Sucursal")
     */
    private $sucursal;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Turno
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Turno")
     */
    private $turno;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Puesto
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Puesto")
     */
    private $puesto;

    /**
     * @var \Planillas\EstructuraBundle\Entity\RolesPuesto
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\RolesPuesto")
     */
    private $rol;

    /**
     * @var $empleado \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="puesto")
     */
    private $empleado;

    public function __construct($empleado)
    {
        $this->empleado = $empleado;
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
     * Set empresa
     *
     * @param  \Planillas\EstructuraBundle\Entity\Empresa $empresa
     * @return CPuestoEmpleado
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
     * Set cliente
     *
     * @param  \Planillas\EstructuraBundle\Entity\Cliente $cliente
     * @return CPuestoEmpleado
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
     * Set sucursal
     *
     * @param  \Planillas\EstructuraBundle\Entity\Sucursal $sucursal
     * @return CPuestoEmpleado
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
     * Set turno
     *
     * @param  \Planillas\EstructuraBundle\Entity\Turno $turno
     * @return CPuestoEmpleado
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
     * Set puesto
     *
     * @param  \Planillas\EstructuraBundle\Entity\Puesto $puesto
     * @return CPuestoEmpleado
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
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CPuestoEmpleado
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
     * @param \Planillas\EstructuraBundle\Entity\RolesPuesto $rol
     */
    public function setRol(\Planillas\EstructuraBundle\Entity\RolesPuesto $rol)
    {
        $this->rol = $rol;
    }

    /**
     * @return \Planillas\EstructuraBundle\Entity\RolesPuesto
     */
    public function getRol()
    {
        return $this->rol;
    }


}
