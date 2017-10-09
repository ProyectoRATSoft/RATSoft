<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblProveedores
 *
 * @ORM\Table(name="tbl_proveedores", indexes={@ORM\Index(name="FK_IVA_idx", columns={"iva"}), @ORM\Index(name="FK_JURISDICCION_idx", columns={"jurisdiccion"}), @ORM\Index(name="FK_JURISDICCION_PROV_idx", columns={"jurisdiccion"}), @ORM\Index(name="IDX_CUIT_PROV_idx", columns={"cuit"})})
 * @ORM\Entity
 */
class TblProveedores
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="cuit", type="bigint", nullable=true)
     */
    private $cuit;

    /**
     * @var integer
     *
     * @ORM\Column(name="activo", type="integer", nullable=true)
     */
    private $activo;

    /**
     * @var \BackendBundle\Entity\TblSituacionIva
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblSituacionIva")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iva", referencedColumnName="id")
     * })
     */
    private $iva;

    /**
     * @var \BackendBundle\Entity\TblJurisdicciones
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblJurisdicciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="jurisdiccion", referencedColumnName="id")
     * })
     */
    private $jurisdiccion;



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
     * @param string $nombre
     *
     * @return TblProveedores
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
     * Set cuit
     *
     * @param integer $cuit
     *
     * @return TblProveedores
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return integer
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set activo
     *
     * @param integer $activo
     *
     * @return TblProveedores
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return integer
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set iva
     *
     * @param \BackendBundle\Entity\TblSituacionIva $iva
     *
     * @return TblProveedores
     */
    public function setIva(\BackendBundle\Entity\TblSituacionIva $iva = null)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return \BackendBundle\Entity\TblSituacionIva
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set jurisdiccion
     *
     * @param \BackendBundle\Entity\TblJurisdicciones $jurisdiccion
     *
     * @return TblProveedores
     */
    public function setJurisdiccion(\BackendBundle\Entity\TblJurisdicciones $jurisdiccion = null)
    {
        $this->jurisdiccion = $jurisdiccion;

        return $this;
    }

    /**
     * Get jurisdiccion
     *
     * @return \BackendBundle\Entity\TblJurisdicciones
     */
    public function getJurisdiccion()
    {
        return $this->jurisdiccion;
    }
}
