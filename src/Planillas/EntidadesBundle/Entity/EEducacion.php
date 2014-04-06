<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EEducacion
 *
 * @ORM\Table(name="e_educacion")
 * @ORM\Entity
 */
class EEducacion
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
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

    /**
     * @var $educacionIdiomas Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EEducacionIdiomas", mappedBy="educacion")
     */
    private $educacionIdiomas;

    /**
     * @var $informacionEducacional Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EInformacionEducacional", mappedBy="educacion")
     */
    private $informacionEducacional;

    /**
     * @var $cursos Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ECursos", mappedBy="educacion")
     */
    private $cursos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->educacionIdiomas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->informacionEducacional = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cursos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EEducacion
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
     * Add educacionIdiomas
     *
     * @param  \Planillas\EntidadesBundle\Entity\EEducacionIdiomas $educacionIdiomas
     * @return EEducacion
     */
    public function addEducacionIdioma(\Planillas\EntidadesBundle\Entity\EEducacionIdiomas $educacionIdiomas)
    {
        $this->educacionIdiomas[] = $educacionIdiomas;

        return $this;
    }

    /**
     * Remove educacionIdiomas
     *
     * @param \Planillas\EntidadesBundle\Entity\EEducacionIdiomas $educacionIdiomas
     */
    public function removeEducacionIdioma(\Planillas\EntidadesBundle\Entity\EEducacionIdiomas $educacionIdiomas)
    {
        $this->educacionIdiomas->removeElement($educacionIdiomas);
    }

    /**
     * Get educacionIdiomas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEducacionIdiomas()
    {
        return $this->educacionIdiomas;
    }

    /**
     * Add informacionEducacional
     *
     * @param  \Planillas\EntidadesBundle\Entity\EInformacionEducacional $informacionEducacional
     * @return EEducacion
     */
    public function addInformacionEducacional(\Planillas\EntidadesBundle\Entity\EInformacionEducacional $informacionEducacional)
    {
        $this->informacionEducacional[] = $informacionEducacional;

        return $this;
    }

    /**
     * Remove informacionEducacional
     *
     * @param \Planillas\EntidadesBundle\Entity\EInformacionEducacional $informacionEducacional
     */
    public function removeInformacionEducacional(\Planillas\EntidadesBundle\Entity\EInformacionEducacional $informacionEducacional)
    {
        $this->informacionEducacional->removeElement($informacionEducacional);
    }

    /**
     * Get informacionEducacional
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInformacionEducacional()
    {
        return $this->informacionEducacional;
    }

    /**
     * Add cursos
     *
     * @param  \Planillas\EntidadesBundle\Entity\ECursos $cursos
     * @return EEducacion
     */
    public function addCurso(\Planillas\EntidadesBundle\Entity\ECursos $cursos)
    {
        $this->cursos[] = $cursos;

        return $this;
    }

    /**
     * Remove cursos
     *
     * @param \Planillas\EntidadesBundle\Entity\ECursos $cursos
     */
    public function removeCurso(\Planillas\EntidadesBundle\Entity\ECursos $cursos)
    {
        $this->cursos->removeElement($cursos);
    }

    /**
     * Get cursos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursos()
    {
        return $this->cursos;
    }

    public function __toString()
    {
        return $this->getEmpleado()->getNombre();
    }
}
