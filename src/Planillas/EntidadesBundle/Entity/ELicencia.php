<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ELicencia
 *
 * @ORM\Table(name="e_licencia")
 * @ORM\Entity
 */
class ELicencia
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
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="licencias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empleado;

    /**
     * @var \DateTime
     * @Assert\Date(),
     * @ORM\Column(name="vence", type="date", nullable=false)
     * @Assert\NotBlank()
     */
    private $vence;

    /**
     * @var $tipo Planillas/NomencladorBundle/Entity/NTipoLicencia
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTipoLicencia")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     *
     */
    private $tipoLicencia;


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
     * Set vence
     *
     * @param \DateTime $vence
     * @return ELicencia
     */
    public function setVence($vence)
    {
        $this->vence = $vence;
    
        return $this;
    }

    /**
     * Get vence
     *
     * @return \DateTime 
     */
    public function getVence()
    {
        return $this->vence;
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

    /**
     * Set tipoLicencia
     *
     * @param \Planillas\NomencladorBundle\Entity\NTipoLicencia $tipoLicencia
     * @return ELicencia
     */
    public function setTipoLicencia(\Planillas\NomencladorBundle\Entity\NTipoLicencia $tipoLicencia = null)
    {
        $this->tipoLicencia = $tipoLicencia;
    
        return $this;
    }

    /**
     * Get tipoLicencia
     *
     * @return \Planillas\NomencladorBundle\Entity\NTipoLicencia 
     */
    public function getTipoLicencia()
    {
        return $this->tipoLicencia;
    }
}