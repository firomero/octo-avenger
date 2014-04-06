<?php

namespace Planillas\EntidadesBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * EHistoriaTrabajo
 *
 * @ORM\Table(name="e_historia_trabajo")
 * @ORM\Entity
 */
class EHistoriaTrabajo
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
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="momento", type="string", length=32, nullable=false)
     */
    private $momento;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="empresa_patrono", type="string", length=64, nullable=true)
     */
    private $empresaPatrono;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     * @Assert\Regex(pattern="/^([0-9]){5,20}$/", message="El teléfono no es correcto")
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @var decimal
     * @Assert\Regex(pattern="/^([0-9]+)|([0-9]+)(,|.)([0-9])+$/", message="El salario no es correcto")
     * @ORM\Column(name="salario", type="decimal", nullable=true)
     */
    private $salario;

    /**
     * @var string
     * @Assert\Regex(pattern="/^(([a-zA-ZñÑáéíóúÁÉÍÓÚ])([ ])*)+$/", message="El nombre no es correcto")
     * @ORM\Column(name="nombre_jefe", type="string", length=64, nullable=true)
     */
    private $nombreJefe;

    /**
     * @var string
     *
     * @ORM\Column(name="puesto", type="string", length=64, nullable=true)
     */
    private $puesto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     */
    private $fechaFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="tiempo", type="integer", nullable=false)
     */
    private $tiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo_salida", type="string", length=255, nullable=true)
     */
    private $motivoSalida;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="historiasTrabajos")
     */
    private $empleado;

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
     * Set momento
     *
     * @param  string           $momento
     * @return EHistoriaTrabajo
     */
    public function setMomento($momento)
    {
        $this->momento = $momento;

        return $this;
    }

    /**
     * Get momento
     *
     * @return string
     */
    public function getMomento()
    {
        return $this->momento;
    }

    /**
     * Set empresaPatrono
     *
     * @param  string           $empresaPatrono
     * @return EHistoriaTrabajo
     */
    public function setEmpresaPatrono($empresaPatrono)
    {
        $this->empresaPatrono = $empresaPatrono;

        return $this;
    }

    /**
     * Get empresaPatrono
     *
     * @return string
     */
    public function getEmpresaPatrono()
    {
        return $this->empresaPatrono;
    }

    /**
     * Set direccion
     *
     * @param  string           $direccion
     * @return EHistoriaTrabajo
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param  string           $telefono
     * @return EHistoriaTrabajo
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set salario
     *
     * @param  float            $salario
     * @return EHistoriaTrabajo
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
     * Set nombreJefe
     *
     * @param  string           $nombreJefe
     * @return EHistoriaTrabajo
     */
    public function setNombreJefe($nombreJefe)
    {
        $this->nombreJefe = $nombreJefe;

        return $this;
    }

    /**
     * Get nombreJefe
     *
     * @return string
     */
    public function getNombreJefe()
    {
        return $this->nombreJefe;
    }

    /**
     * Set puesto
     *
     * @param  string           $puesto
     * @return EHistoriaTrabajo
     */
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Get puesto
     *
     * @return string
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * Set fechaInicio
     *
     * @param  \DateTime        $fechaInicio
     * @return EHistoriaTrabajo
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
     * Set fechaFin
     *
     * @param  \DateTime        $fechaFin
     * @return EHistoriaTrabajo
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set tiempo
     *
     * @param  integer          $tiempo
     * @return EHistoriaTrabajo
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return integer
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set motivoSalida
     *
     * @param  string           $motivoSalida
     * @return EHistoriaTrabajo
     */
    public function setMotivoSalida($motivoSalida)
    {
        $this->motivoSalida = $motivoSalida;

        return $this;
    }

    /**
     * Get motivoSalida
     *
     * @return string
     */
    public function getMotivoSalida()
    {
        return $this->motivoSalida;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EHistoriaTrabajo
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
    * @Assert\True(message = "La fecha de inicial debe ser mayor que la fecha final")
    */
    public function isFechaInicioValid()
    {
       return $this->fechaInicio->getTimestamp()<=$this->fechaFin->getTimestamp();
    }
    /**
    * @Assert\True(message = "La fecha final no puede ser mayor que la fecha actual")
    */
    public function isFechaFinValid()
    {
       return $this->fechaFin->getTimestamp() <  time();
    }

}
