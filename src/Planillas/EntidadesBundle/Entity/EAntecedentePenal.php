<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EAntecedentePenal
 *
 * @ORM\Table(name="e_antecedente_penal")
 * @ORM\Entity
 */
class EAntecedentePenal
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
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="antecedentespenales")
     */
    private $empleado;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", length=64, nullable=false)
     */
    private $motivo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     */
    private $descripcion;


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
     * Set motivo
     *
     * @param string $motivo
     * @return EAntecedentePenal
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    
        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return EAntecedentePenal
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
     * Get empleado
     *
     * @return \Planillas\CoreBundle\Entity\CEmpleado
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return this
     */
    public function setEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
        return $this;
    }
}