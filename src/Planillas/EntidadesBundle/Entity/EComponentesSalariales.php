<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * EComponentesSalariales
 *
 * @ORM\Table(name="e_componentes_salariales")
 * @ORM\Entity
 */
class EComponentesSalariales
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
     * @var integer
     *
     * @ORM\Column(name="componente", type="integer", nullable=false)
     */
    private $componente;

    /**
     * @var decimal
     * @Assert\Regex(pattern="/^([1-9])?.([0-9])+$/", message="La cantidad no es correcta")
     * @ORM\Column(name="cantidad", type="decimal", nullable=true)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="moneda", type="integer", nullable=true)
     */
    private $moneda;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var date
     *
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;
    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="componentesSalariales")
     */
    private $empleado;
    /**
     * @var float
     *
     * @ORM\Column(name="monto_total", type="decimal", scale=2, nullable=true)
     */
    private $montoTotal;
    /**
     * @var float
     *
     * @ORM\Column(name="monto_reducir", type="decimal", scale=2, nullable=true)
     */
    private $montoReducir;
    /**
     * @var integer
     *
     * @ORM\Column(name="periodo_pago", type="integer", nullable=true)
     */
    private $periodoPagoDeuda;
    /**
     * @var float
     *
     * @ORM\Column(name="numero_cuotas", type="decimal", scale=2, nullable=true)
     */
    private $numeroCuotas;
    /**
     * @var float
     *
     * @ORM\Column(name="monto_restante", type="decimal", scale=2, nullable=true)
     */
    private $montoRestante;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;
    /**
     * @var \Boolean
     *
     * @ORM\Column(name="pagado", type="boolean", nullable=true)
     */
    private $pagado;
    /**
     * @var string
     *
     * @ORM\Column(name="tipo_deuda", type="string", nullable=true)
     */
    private $tipoDeuda;
    /**
     * @var $planilla Planillas/CoreBundle/Entity/CPlanillas
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillas")
     */
    private $planilla;
    /**
     * @var \Boolean
     *
     * @ORM\Column(name="permanente", type="boolean", nullable=true)
     */
    private $permanente;
     /**
     * @var \date
     *
     * @ORM\Column(name="deleted_at", type="date", nullable=true)
     */
    private $deleted_at;

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
     * Set componente
     *
     * @param  integer                $componente
     * @return EComponentesSalariales
     */
    public function setComponente($componente)
    {
        $this->componente = $componente;

        return $this;
    }

    /**
     * Get componente
     *
     * @return integer
     */
    public function getComponente()
    {
        return $this->componente;
    }

    /**
     * Set cantidad
     *
     * @param  float                  $cantidad
     * @return EComponentesSalariales
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set moneda
     *
     * @param  integer                $moneda
     * @return EComponentesSalariales
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda
     *
     * @return integer
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set periodoPagoDeuda
     *
     * @param integer periodoPagoDeuda
     * @return EComponentesSalariales
     */
    public function setPeriodoPagoDeuda($periodoPagoDeuda)
    {
        $this->periodoPagoDeuda = $periodoPagoDeuda;

        return $this;
    }

    /**
     * Get periodoPagoDeuda
     *
     * @return integer
     */
    public function getPeriodoPagoDeuda()
    {
        return $this->periodoPagoDeuda;
    }
    /**
     * Set descripcion
     *
     * @param  string                 $descripcion
     * @return EComponentesSalariales
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaVencimiento
     *
     * @param  \DateTime              $fechaVencimiento
     * @return EComponentesSalariales
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EComponentesSalariales
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
     * Set montoTotal
     *
     * @param  float                  $montoTotal
     * @return EComponentesSalariales
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
     * @param  float                  $montoReducir
     * @return EComponentesSalariales
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
     * @param  float                  $numeroCuotas
     * @return EComponentesSalariales
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
     * @param  float                  $montoRestante
     * @return EComponentesSalariales
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
     * @param  \DateTime              $fechaInicio
     * @return EComponentesSalariales
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
     * Set pagado
     *
     * @param  boolean                $pagado
     * @return EComponentesSalariales
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
     * @param  string                 $tipoDeuda
     * @return EComponentesSalariales
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

    /**
     * Set planilla
     *
     * @param  \Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @return EComponentesSalariales
     */
    public function setPlanilla(\Planillas\CoreBundle\Entity\CPlanillas $planilla = null)
    {
        $this->planilla = $planilla;

        return $this;
    }

    /**
     * Get planilla
     *
     * @return \Planillas\CoreBundle\Entity\CPlanillas
     */
    public function getPlanilla()
    {
        return $this->planilla;
    }

    /**
     * Set permanente
     *
     * @param  boolean                $permanente
     * @return EComponentesSalariales
     */
    public function setPermanente($permanente)
    {
        $this->permanente = $permanente;

        return $this;
    }

    /**
     * Get permanente
     *
     * @return boolean
     */
    public function getPermanente()
    {
        return $this->permanente;
    }

    /**
     * Set deleted_at
     *
     * @param  \DateTime              $deletedAt
     * @return EComponentesSalariales
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * Get deleted_at
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }
    public function __toString()
    {
        return $this->getPermanente();
        ;
    }
}
