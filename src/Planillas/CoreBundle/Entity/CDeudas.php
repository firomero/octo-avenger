<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CHorasExtras
 *
 * @ORM\Table(name="c_deudas")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CDeudasRepository")
 */
class CDeudas
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
     * @var float
     *
     * @ORM\Column(name="monto_total", type="decimal", scale=2, nullable=false)
     * @Assert\Range(min = 1)
     */
    private $montoTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="monto_reducir", type="decimal", scale=2, nullable=false)
     * @Assert\Range(min = 1)
     */
    private $montoReducir;

    /**
     * @var float
     *
     * @ORM\Column(name="numero_cuotas", type="decimal", scale=2, nullable=false)
     */
    private $numeroCuotas;

    /**
     * @var float
     *
     * @ORM\Column(name="monto_restante", type="decimal", scale=2, nullable=true)
     * @Assert\Range(min = 1)
     */
    private $montoRestante;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \Boolean
     *
     * @ORM\Column(name="pagado", type="boolean", nullable=false)
     */
    private $pagado;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_deuda", type="string", nullable=false)
     */
    private $tipoDeuda;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

    /**
     * @var  \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillasEmpleado", inversedBy="deudas")
     */
    private $planillaEmpleado;

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
     * Set montoTotal
     *
     * @param  float   $montoTotal
     * @return CDeudas
     */
    public function setMontoTotal($montoTotal)
    {
        $this->montoTotal = $montoTotal;

        return $this;
    }

    /**
     * Get montoTotal
     *
     * @return float
     */
    public function getMontoTotal()
    {
        return $this->montoTotal;
    }

    /**
     * Set montoReducir
     *
     * @param  float   $montoReducir
     * @return CDeudas
     */
    public function setMontoReducir($montoReducir)
    {
        $this->montoReducir = $montoReducir;

        return $this;
    }

    /**
     * Get montoReducir
     *
     * @return float
     */
    public function getMontoReducir()
    {
        return $this->montoReducir;
    }

    /**
     * Set numeroCuotas
     *
     * @param  float   $numeroCuotas
     * @return CDeudas
     */
    public function setNumeroCuotas($numeroCuotas)
    {
        $this->numeroCuotas = $numeroCuotas;

        return $this;
    }

    /**
     * Get numeroCuotas
     *
     * @return float
     */
    public function getNumeroCuotas()
    {
        return $this->numeroCuotas;
    }

    /**
     * Set montoRestante
     *
     * @param  float   $montoRestante
     * @return CDeudas
     */
    public function setMontoRestante($montoRestante)
    {

        $this->montoRestante = $montoRestante;

        return $this;
    }

    /**
     * Get montoRestante
     *
     * @return float
     */
    public function getMontoRestante()
    {
        return $this->montoRestante;
    }

    /**
     * Set fechaInicio
     *
     * @param  \DateTime $fechaInicio
     * @return CDeudas
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CDeudas
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
     * @ORM\PrePersist()
     */
    public function setMontoRestanteManual()
    {
      if (null==$this->montoRestante) {
          $this->setMontoRestante($this->montoTotal);
      }
    }

    /**
     * Set pagado
     *
     * @param  boolean $pagado
     * @return CDeudas
     */
    public function setPagado($pagado)
    {
        $this->pagado = $pagado;

        return $this;
    }

    /**
     * Get pagado
     *
     * @return boolean
     */
    public function getPagado()
    {
        return $this->pagado;
    }

    /**
     * Set tipoDeuda
     *
     * @param  string  $tipoDeuda
     * @return CDeudas
     */
    public function setTipoDeuda($tipoDeuda)
    {
        $this->tipoDeuda = $tipoDeuda;

        return $this;
    }

    /**
     * Get tipoDeuda
     *
     * @return string
     */
    public function getTipoDeuda()
    {
        return $this->tipoDeuda;
    }

    public function getJson()
    {
        $obj= new \stdClass();
        $obj->id=$this->id;
        $obj->fechaInicio=$this->fechaInicio->format('Y-m-d');
        $obj->tipoDeuda=$this->tipoDeuda;
        $obj->empleado=$this->empleado->getId();
        $obj->montoTotal=$this->montoTotal;
        $obj->montoReducir=$this->montoReducir;
        $obj->numeroCuotas=$this->numeroCuotas;
        $obj->pagado=$this->pagado;

        return $obj;
    }

    /**
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     */
    public function setPlanillaEmpleado(CPlanillasEmpleado $planillaEmpleado)
    {
        $this->planillaEmpleado = $planillaEmpleado;
    }

    /**
     * @return \Planillas\CoreBundle\Entity\CPlanillasEmpleado
     */
    public function getPlanillaEmpleado()
    {
        return $this->planillaEmpleado;
    }
}
