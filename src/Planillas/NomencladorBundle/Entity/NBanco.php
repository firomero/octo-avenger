<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Planillas\CoreBundle\Entity\CEmpleado;
//use Planillas\NomencladorBundle\Entity\NBanco;
/**
 * NBanco
 *
 * @ORM\Table(name="n_banco")
 * @ORM\Entity
 */
class NBanco
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
     *
     * @ORM\Column(name="nombre", type="string", length=40, nullable=true)
     */
    private $nombre;
 
    /**
     * @ORM\ManyToMany(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", mappedBy="cuentasBancos")
     **/
    private $empleados;

    /**
     * Get bancoId
     *
     * @return integer 
     */
    public function getBancoId()
    {
        return $this->bancoId;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return NBanco
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    /**
     * Set empleado
     *
     * @return void
     */
    public function addEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado)
    {
        $empleado->addCuentasBanco($this);
        $this->empleados->add($empleado);
        return $this;
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
    
    function __construct(){
        $this->empleados = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    function __toString(){
        return $this->nombre;
    }
}