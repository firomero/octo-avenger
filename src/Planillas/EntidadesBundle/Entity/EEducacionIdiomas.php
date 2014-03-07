<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * EEducacionIdiomas
 *
 * @ORM\Table(name="e_educacion_idiomas")
 * @ORM\Entity
 */
class EEducacionIdiomas
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
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="idiomas")
     */
    private $empleado;
    /**
     * @var decimal
     * @ORM\Column(name="porciento_idioma", type="decimal", nullable=false)
     * @Assert\Regex(pattern="/^(100)|[1-9]([0-9]){0,1}$/", message="El porciento no es correcto")
     * @Assert\Length(min=1, max=3)
     */
    private $porientoIdioma;

    /**
     * @var $idioma Planillas/NomencladorBundle/Entity/NIdioma
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NIdioma")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $idioma;


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
     * Set porientoIdioma
     *
     * @param float $porientoIdioma
     * @return EEducacionIdiomas
     */
    public function setPorientoIdioma($porientoIdioma)
    {
        $this->porientoIdioma = $porientoIdioma;

        return $this;
    }

    /**
     * Get porientoIdioma
     *
     * @return float
     */
    public function getPorientoIdioma()
    {
        return $this->porientoIdioma;
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
     * @return EFamilia
     */
    public function setEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
        return $this;
    }
    /**
     * Set idioma
     *
     * @param \Planillas\NomencladorBundle\Entity\NIdioma $idioma
     * @return EEducacionIdiomas
     */
    public function setIdioma(\Planillas\NomencladorBundle\Entity\NIdioma $idioma = null)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * Get idioma
     *
     * @return \Planillas\NomencladorBundle\Entity\NIdioma
     */
    public function getIdioma()
    {
        return $this->idioma;
    }
   
}