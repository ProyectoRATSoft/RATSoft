<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblEmpresas
 *
 * @ORM\Table(name="tbl_empresas", indexes={@ORM\Index(name="FK_PROVINCIA_idx", columns={"provincia"}), @ORM\Index(name="FK_IVA_idx", columns={"iva"}), @ORM\Index(name="FK_RUBRO_idx", columns={"rubro"})})
 * @ORM\Entity
 */
class TblEmpresas
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
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=45, nullable=true)
     */
    private $domicilio;

    /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=45, nullable=true)
     */
    private $localidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="cuit", type="bigint", nullable=true)
     */
    private $cuit;

    /**
     * @var integer
     *
     * @ORM\Column(name="iibb", type="integer", nullable=true)
     */
    private $iibb;

    /**
     * @var string
     *
     * @ORM\Column(name="titular", type="string", length=45, nullable=true)
     */
    private $titular;

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
     * @var \BackendBundle\Entity\TblProvincias
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblProvincias")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="provincia", referencedColumnName="id")
     * })
     */
    private $provincia;

    /**
     * @var \BackendBundle\Entity\TblRubros
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblRubros")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rubro", referencedColumnName="id")
     * })
     */
    private $rubro;



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
     * @return TblEmpresas
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
     * Set domicilio
     *
     * @param string $domicilio
     *
     * @return TblEmpresas
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio
     *
     * @return string
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     *
     * @return TblEmpresas
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set cuit
     *
     * @param integer $cuit
     *
     * @return TblEmpresas
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
     * Set iibb
     *
     * @param integer $iibb
     *
     * @return TblEmpresas
     */
    public function setIibb($iibb)
    {
        $this->iibb = $iibb;

        return $this;
    }

    /**
     * Get iibb
     *
     * @return integer
     */
    public function getIibb()
    {
        return $this->iibb;
    }

    /**
     * Set titular
     *
     * @param string $titular
     *
     * @return TblEmpresas
     */
    public function setTitular($titular)
    {
        $this->titular = $titular;

        return $this;
    }

    /**
     * Get titular
     *
     * @return string
     */
    public function getTitular()
    {
        return $this->titular;
    }

    /**
     * Set activo
     *
     * @param integer $activo
     *
     * @return TblEmpresas
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
     * @return TblEmpresas
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
     * Set provincia
     *
     * @param \BackendBundle\Entity\TblProvincias $provincia
     *
     * @return TblEmpresas
     */
    public function setProvincia(\BackendBundle\Entity\TblProvincias $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return \BackendBundle\Entity\TblProvincias
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set rubro
     *
     * @param \BackendBundle\Entity\TblRubros $rubro
     *
     * @return TblEmpresas
     */
    public function setRubro(\BackendBundle\Entity\TblRubros $rubro = null)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro
     *
     * @return \BackendBundle\Entity\TblRubros
     */
    public function getRubro()
    {
        return $this->rubro;
    }
}
