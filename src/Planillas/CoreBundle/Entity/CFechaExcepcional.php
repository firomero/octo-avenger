<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CFechaExcepcional
 *
 * @ORM\Table(name="c_fecha_excepcional")
 * @ORM\Entity
 */
class CFechaExcepcional
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
     * @var $horario CHorario
     *
     * @ORM\ManyToOne(targetEntity="CHorario", inversedBy="fechaexcepcional")
     */
    private $horario;

    /**
     * @var $empleado CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="CEmpleado", inversedBy="fechaexcepcional")
     */
    private $empleado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var string
     * @ORM\Column(name="observacion", type="string", length=254, nullable=true)
     */
    private $observacion;

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
     * Set fecha
     *
     * @param  \DateTime         $fecha
     * @return CFechaExcepcional
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set horario
     *
     * @param  \Planillas\CoreBundle\Entity\CHorario $horario
     * @return CFechaExcepcional
     */
    public function setHorario(\Planillas\CoreBundle\Entity\CHorario $horario = null)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return \Planillas\CoreBundle\Entity\CHorario
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set observacion
     *
     * @param  string            $observacion
     * @return CFechaExcepcional
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CFechaExcepcional
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
}
