<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ECuentaBanco
 *
 * @ORM\Table(name="e_cuenta_banco_empleado")
 * @ORM\Entity(repositoryClass="Planillas\EntidadesBundle\Entity\Repository\ECuentaBancoRepository")
 */
class ECuentaBanco
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
     * @var  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="cuentasBancos")
     */
    private $empleado;

    /**
     * @var  \Planillas\NomencladorBundle\Entity\NBanco $banco
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NBanco")
     */
    private $banco;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=32)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="nrocuenta", type="string", length=255, nullable=true)
     */
    private $nrocuenta;


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
     * Set tipo
     *
     * @param string $tipo
     * @return ECuentaBanco
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return ECuentaBanco
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
     * Set banco
     *
     * @param \Planillas\NomencladorBundle\Entity\NBanco $banco
     * @return ECuentaBanco
     */
    public function setBanco(\Planillas\NomencladorBundle\Entity\NBanco $banco = null)
    {
        $this->banco = $banco;
    
        return $this;
    }

    /**
     * Get banco
     *
     * @return \Planillas\NomencladorBundle\Entity\NBanco 
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set nrocuenta
     *
     * @param string $nrocuenta
     * @return ECuentaBanco
     */
    public function setNrocuenta($nrocuenta)
    {
        $this->nrocuenta = $nrocuenta;
    
        return $this;
    }

    /**
     * Get nrocuenta
     *
     * @return string 
     */
    public function getNrocuenta()
    {
        return $this->nrocuenta;
    }
}