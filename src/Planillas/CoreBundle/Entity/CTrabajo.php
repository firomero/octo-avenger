<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CTrabajo
 *
 * @ORM\Table(name="c_trabajo")
 * @ORM\Entity
 */
class CTrabajo
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
     * @var $trabajo NTrabajo
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTrabajo")
     */
    private $nombre;
    /**
     * @var date
     *
     * @ORM\Column(name="fechaTrabajo", type="date", nullable=true)
     */
    private $fechaTrabajo;
    /**
     * @var $empleado CEmpleado
     *
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="trabajo")
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
     * Set nombre
     *
     * @param  \Planillas\NomencladorBundle\Entity\NTrabajo $nombre
     * @return CTrabajo
     */
    public function setNombre(\Planillas\NomencladorBundle\Entity\NTrabajo $nombre = null)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return \Planillas\NomencladorBundle\Entity\NTrabajo
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaTrabajo
     *
     * @param  date     $fechaTrabajo
     * @return CTrabajo
     */
    public function setFechaTrabajo($fechaTrabajo)
    {
        $this->fechaTrabajo = $fechaTrabajo;

        return $this;
    }

    /**
     * Get fechaTrabajo
     *
     * @return date
     */
    public function getFechaTrabajo()
    {
        return $this->fechaTrabajo;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CTrabajo
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
