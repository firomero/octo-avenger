<?php

namespace Planillas\CoreBundle\Form\Models;

use Planillas\CoreBundle\Entity\CEmpleado;
use Symfony\Component\Validator\Constraints as Assert;

class SalarioBasePuesto
{
    private $id;

    /**
     * @var float
     * @Assert\NotBlank()
     */
    private $salarioBase;

    /**
     * @var boolean
     */
    private $seguro;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Empresa
     */
    private $empresa;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Cliente
     */
    private $cliente;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Sucursal
     */
    private $sucursal;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Turno
     */
    private $turno;

    /**
     * @var \Planillas\EstructuraBundle\Entity\Puesto
     */
    private $puesto;

    /**
     * @var \Planillas\EstructuraBundle\Entity\RolesPuesto
     */
    private $rol;

    public function __construct(CEmpleado $empleado = null)
    {
        if ($empleado != null) {
            if ($empleado->getSalarioBase() != null) {
                $salarioBase = $empleado->getSalarioBase();
                $this->setSalarioBase($salarioBase->getSalarioBase());
                $this->setSeguro($salarioBase->getSeguro());
            }
            if ($empleado->getPuesto() != null) {
                $puesto = $empleado->getPuesto();
                $this->setEmpresa($puesto->getEmpresa());
                $this->setCliente($puesto->getCliente());
                $this->setSucursal($puesto->getSucursal());
                $this->setTurno($puesto->getTurno());
                $this->setPuesto($puesto->getPuesto());
                $this->setRol($puesto->getRol());
            }
            $this->id = $empleado->getId();
        }
    }
    /**
     * @param \Planillas\EstructuraBundle\Entity\Cliente $cliente
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * @return \Planillas\EstructuraBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @param \Planillas\EstructuraBundle\Entity\Empresa $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return \Planillas\EstructuraBundle\Entity\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param \Planillas\EstructuraBundle\Entity\Puesto $puesto
     */
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;
    }

    /**
     * @return \Planillas\EstructuraBundle\Entity\Puesto
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * @param float $salarioBase
     */
    public function setSalarioBase($salarioBase)
    {
        $this->salarioBase = $salarioBase;
    }

    /**
     * @return float
     */
    public function getSalarioBase()
    {
        return $this->salarioBase;
    }

    /**
     * @param boolean $seguro
     */
    public function setSeguro($seguro)
    {
        $this->seguro = $seguro;
    }

    /**
     * @return boolean
     */
    public function getSeguro()
    {
        return $this->seguro;
    }

    /**
     * @param \Planillas\EstructuraBundle\Entity\Sucursal $sucursal
     */
    public function setSucursal($sucursal)
    {
        $this->sucursal = $sucursal;
    }

    /**
     * @return \Planillas\EstructuraBundle\Entity\Sucursal
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * @param \Planillas\EstructuraBundle\Entity\Turno $turno
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;
    }

    /**
     * @return \Planillas\EstructuraBundle\Entity\Turno
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * @param \Planillas\EstructuraBundle\Entity\RolesPuesto $rol
     */
    public function setRol($rol)
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

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



}
