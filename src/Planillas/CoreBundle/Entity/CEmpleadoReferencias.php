<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CEmpleadoReferencias
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class CEmpleadoReferencias
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCompletado", type="date")
     */
    private $fechaCompletado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="compentarios", type="string", length=1000)
     */
    private $compentarios;

    /**
     * @var \Planillas\NomencladorBundle\Entity\NClasificacionReferencia
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NClasificacionReferencia")
     */
    private $clasificacionReferencia;

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Planillas\NomencladorBundle\Entity\NClasificacionReferencia $clasificacionReferencia
     */
    public function setClasificacionReferencia($clasificacionReferencia)
    {
        $this->clasificacionReferencia = $clasificacionReferencia;
    }

    /**
     * @return \Planillas\NomencladorBundle\Entity\NClasificacionReferencia
     */
    public function getClasificacionReferencia()
    {
        return $this->clasificacionReferencia;
    }

    /**
     * @param string $compentarios
     */
    public function setCompentarios($compentarios)
    {
        $this->compentarios = $compentarios;
    }

    /**
     * @return string
     */
    public function getCompentarios()
    {
        return $this->compentarios;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $fechaCompletado
     */
    public function setFechaCompletado($fechaCompletado)
    {
        $this->fechaCompletado = $fechaCompletado;
    }

    /**
     * @return \DateTime
     */
    public function getFechaCompletado()
    {
        return $this->fechaCompletado;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
