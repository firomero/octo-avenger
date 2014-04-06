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
     * @var \Planillas\CoreBundle\Entity\CHorario
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CHorario")
     * @Assert\NotBlank()
     */
    private $rol;

    /**
     * @var float
     *
     * @ORM\Column(name="salario", type="float")
     * @Assert\NotBlank()
     */
    private $salario;

    /**
     * @var \Planillas\NomencladorBundle\Entity\NBonificacionPuesto
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NBonificacionPuesto")
     * @Assert\NotBlank()
     */
    private $bonificacion;

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
     * Set rol
     *
     * @param  \Planillas\CoreBundle\Entity\CHorario $rol
     * @return Puesto
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
     * Set bonificacion
     *
     * @param  \Planillas\NomencladorBundle\Entity\NBonificacionPuesto $bonificacion
     * @return Puesto
     */
    public function setBonificacion(\Planillas\NomencladorBundle\Entity\NBonificacionPuesto $bonificacion = null)
    {
        $this->bonificacion = $bonificacion;

        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return \Planillas\NomencladorBundle\Entity\NBonificacionPuesto
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }
}
