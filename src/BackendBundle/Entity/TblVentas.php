<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblVentas
 *
 * @ORM\Table(name="tbl_ventas", indexes={@ORM\Index(name="FK_COMPROBANTE_idx", columns={"cod_comprobante"}), @ORM\Index(name="FK_TIPO_COMP_idx", columns={"tipo_comprobante"}), @ORM\Index(name="FK_CLIENTES_idx", columns={"cliente"})})
 * @ORM\Entity
 */
class TblVentas
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
     * @var integer
     *
     * @ORM\Column(name="periodo_mes", type="integer", nullable=false)
     */
    private $periodoMes;

    /**
     * @var integer
     *
     * @ORM\Column(name="periodo_ano", type="integer", nullable=false)
     */
    private $periodoAno;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="nro_comprobante", type="integer", nullable=false)
     */
    private $nroComprobante;

    /**
     * @var string
     *
     * @ORM\Column(name="neto_reventa", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $netoReventa;

    /**
     * @var string
     *
     * @ORM\Column(name="neto_fabric", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $netoFabric;

    /**
     * @var string
     *
     * @ORM\Column(name="neto_exento", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $netoExento;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_ri", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $ivaRi;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_rni", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $ivaRni;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_snc", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $ivaSnc;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_mon", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $ivaMon;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_cf", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $ivaCf;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_exento", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $ivaExento;

    /**
     * @var string
     *
     * @ORM\Column(name="retencion", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $retencion;

    /**
     * @var string
     *
     * @ORM\Column(name="percepcion", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $percepcion;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $total;

    /**
     * @var \BackendBundle\Entity\TblProveedores
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblProveedores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente", referencedColumnName="id")
     * })
     */
    private $cliente;

    /**
     * @var \BackendBundle\Entity\TblComprobantes
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblComprobantes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cod_comprobante", referencedColumnName="id")
     * })
     */
    private $codComprobante;

    /**
     * @var \BackendBundle\Entity\TblTiposComp
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblTiposComp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_comprobante", referencedColumnName="id")
     * })
     */
    private $tipoComprobante;



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
     * Set periodoMes
     *
     * @param integer $periodoMes
     *
     * @return TblVentas
     */
    public function setPeriodoMes($periodoMes)
    {
        $this->periodoMes = $periodoMes;

        return $this;
    }

    /**
     * Get periodoMes
     *
     * @return integer
     */
    public function getPeriodoMes()
    {
        return $this->periodoMes;
    }

    /**
     * Set periodoAno
     *
     * @param integer $periodoAno
     *
     * @return TblVentas
     */
    public function setPeriodoAno($periodoAno)
    {
        $this->periodoAno = $periodoAno;

        return $this;
    }

    /**
     * Get periodoAno
     *
     * @return integer
     */
    public function getPeriodoAno()
    {
        return $this->periodoAno;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TblVentas
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set nroComprobante
     *
     * @param integer $nroComprobante
     *
     * @return TblVentas
     */
    public function setNroComprobante($nroComprobante)
    {
        $this->nroComprobante = $nroComprobante;

        return $this;
    }

    /**
     * Get nroComprobante
     *
     * @return integer
     */
    public function getNroComprobante()
    {
        return $this->nroComprobante;
    }

    /**
     * Set netoReventa
     *
     * @param string $netoReventa
     *
     * @return TblVentas
     */
    public function setNetoReventa($netoReventa)
    {
        $this->netoReventa = $netoReventa;

        return $this;
    }

    /**
     * Get netoReventa
     *
     * @return string
     */
    public function getNetoReventa()
    {
        return $this->netoReventa;
    }

    /**
     * Set netoFabric
     *
     * @param string $netoFabric
     *
     * @return TblVentas
     */
    public function setNetoFabric($netoFabric)
    {
        $this->netoFabric = $netoFabric;

        return $this;
    }

    /**
     * Get netoFabric
     *
     * @return string
     */
    public function getNetoFabric()
    {
        return $this->netoFabric;
    }

    /**
     * Set netoExento
     *
     * @param string $netoExento
     *
     * @return TblVentas
     */
    public function setNetoExento($netoExento)
    {
        $this->netoExento = $netoExento;

        return $this;
    }

    /**
     * Get netoExento
     *
     * @return string
     */
    public function getNetoExento()
    {
        return $this->netoExento;
    }

    /**
     * Set ivaRi
     *
     * @param string $ivaRi
     *
     * @return TblVentas
     */
    public function setIvaRi($ivaRi)
    {
        $this->ivaRi = $ivaRi;

        return $this;
    }

    /**
     * Get ivaRi
     *
     * @return string
     */
    public function getIvaRi()
    {
        return $this->ivaRi;
    }

    /**
     * Set ivaRni
     *
     * @param string $ivaRni
     *
     * @return TblVentas
     */
    public function setIvaRni($ivaRni)
    {
        $this->ivaRni = $ivaRni;

        return $this;
    }

    /**
     * Get ivaRni
     *
     * @return string
     */
    public function getIvaRni()
    {
        return $this->ivaRni;
    }

    /**
     * Set ivaSnc
     *
     * @param string $ivaSnc
     *
     * @return TblVentas
     */
    public function setIvaSnc($ivaSnc)
    {
        $this->ivaSnc = $ivaSnc;

        return $this;
    }

    /**
     * Get ivaSnc
     *
     * @return string
     */
    public function getIvaSnc()
    {
        return $this->ivaSnc;
    }

    /**
     * Set ivaMon
     *
     * @param string $ivaMon
     *
     * @return TblVentas
     */
    public function setIvaMon($ivaMon)
    {
        $this->ivaMon = $ivaMon;

        return $this;
    }

    /**
     * Get ivaMon
     *
     * @return string
     */
    public function getIvaMon()
    {
        return $this->ivaMon;
    }

    /**
     * Set ivaCf
     *
     * @param string $ivaCf
     *
     * @return TblVentas
     */
    public function setIvaCf($ivaCf)
    {
        $this->ivaCf = $ivaCf;

        return $this;
    }

    /**
     * Get ivaCf
     *
     * @return string
     */
    public function getIvaCf()
    {
        return $this->ivaCf;
    }

    /**
     * Set ivaExento
     *
     * @param string $ivaExento
     *
     * @return TblVentas
     */
    public function setIvaExento($ivaExento)
    {
        $this->ivaExento = $ivaExento;

        return $this;
    }

    /**
     * Get ivaExento
     *
     * @return string
     */
    public function getIvaExento()
    {
        return $this->ivaExento;
    }

    /**
     * Set retencion
     *
     * @param string $retencion
     *
     * @return TblVentas
     */
    public function setRetencion($retencion)
    {
        $this->retencion = $retencion;

        return $this;
    }

    /**
     * Get retencion
     *
     * @return string
     */
    public function getRetencion()
    {
        return $this->retencion;
    }

    /**
     * Set percepcion
     *
     * @param string $percepcion
     *
     * @return TblVentas
     */
    public function setPercepcion($percepcion)
    {
        $this->percepcion = $percepcion;

        return $this;
    }

    /**
     * Get percepcion
     *
     * @return string
     */
    public function getPercepcion()
    {
        return $this->percepcion;
    }

    /**
     * Set total
     *
     * @param string $total
     *
     * @return TblVentas
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set cliente
     *
     * @param \BackendBundle\Entity\TblProveedores $cliente
     *
     * @return TblVentas
     */
    public function setCliente(\BackendBundle\Entity\TblProveedores $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \BackendBundle\Entity\TblProveedores
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set codComprobante
     *
     * @param \BackendBundle\Entity\TblComprobantes $codComprobante
     *
     * @return TblVentas
     */
    public function setCodComprobante(\BackendBundle\Entity\TblComprobantes $codComprobante = null)
    {
        $this->codComprobante = $codComprobante;

        return $this;
    }

    /**
     * Get codComprobante
     *
     * @return \BackendBundle\Entity\TblComprobantes
     */
    public function getCodComprobante()
    {
        return $this->codComprobante;
    }

    /**
     * Set tipoComprobante
     *
     * @param \BackendBundle\Entity\TblTiposComp $tipoComprobante
     *
     * @return TblVentas
     */
    public function setTipoComprobante(\BackendBundle\Entity\TblTiposComp $tipoComprobante = null)
    {
        $this->tipoComprobante = $tipoComprobante;

        return $this;
    }

    /**
     * Get tipoComprobante
     *
     * @return \BackendBundle\Entity\TblTiposComp
     */
    public function getTipoComprobante()
    {
        return $this->tipoComprobante;
    }
}
