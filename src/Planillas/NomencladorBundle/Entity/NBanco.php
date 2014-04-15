<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="nombre", type="string", length=64, nullable=true)
     */
    private $nombre;

    /**
     * @var int $tamannoNumeroCuenta
     *
     * @ORM\Column(name="tamanno_numero_cuenta", type="integer", nullable=true)
     */
    private $tamannoNumeroCuenta;

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
     * @param  string $nombre
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->empleados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Set tamannoNumeroCuenta
     *
     * @param integer $tamannoNumeroCuenta
     * @return NBanco
     */
    public function setTamannoNumeroCuenta($tamannoNumeroCuenta)
    {
        $this->tamannoNumeroCuenta = $tamannoNumeroCuenta;
    
        return $this;
    }

    /**
     * Get tamannoNumeroCuenta
     *
     * @return integer 
     */
    public function getTamannoNumeroCuenta()
    {
        return $this->tamannoNumeroCuenta;
    }
}