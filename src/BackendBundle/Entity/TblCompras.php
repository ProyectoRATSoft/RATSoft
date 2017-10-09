<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblCompras
 *
 * @ORM\Table(name="tbl_compras", indexes={@ORM\Index(name="FK_COMPROBANTE_idx", columns={"cod_comprobante"}), @ORM\Index(name="FK_TIPO_COMP_idx", columns={"tipo_comprobante"}), @ORM\Index(name="FK_IMPUTACION_idx", columns={"imputacion"}), @ORM\Index(name="FK_CLIENTES_idx", columns={"proveedor"}), @ORM\Index(name="FK_EMPRESA_COMPRA_idx", columns={"empresa"})})
 * @ORM\Entity
 */
class TblCompras
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=false)
     */
    private $fechaIngreso;

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
     * @var string
     *
     * @ORM\Column(name="nro_comprobante", type="string", length=45, nullable=false)
     */
    private $nroComprobante;

    /**
     * @var integer
     *
     * @ORM\Column(name="cai", type="bigint", nullable=true)
     */
    private $cai = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="neto_105", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $neto105;

    /**
     * @var string
     *
     * @ORM\Column(name="neto_21", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $neto21;

    /**
     * @var string
     *
     * @ORM\Column(name="neto_27", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $neto27;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_105", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $iva105;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_21", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $iva21;

    /**
     * @var string
     *
     * @ORM\Column(name="iva_27", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $iva27;

    /**
     * @var string
     *
     * @ORM\Column(name="nogravado", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $nogravado;

    /**
     * @var string
     *
     * @ORM\Column(name="exento", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $exento;

    /**
     * @var string
     *
     * @ORM\Column(name="perc_iva", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $percIva;

    /**
     * @var string
     *
     * @ORM\Column(name="perc_iibb", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $percIibb;

    /**
     * @var string
     *
     * @ORM\Column(name="ret_ganancia", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $retGanancia;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $total;

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
     * @var \BackendBundle\Entity\TblEmpresas
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblEmpresas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa", referencedColumnName="id")
     * })
     */
    private $empresa;

    /**
     * @var \BackendBundle\Entity\TblImputaciones
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblImputaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="imputacion", referencedColumnName="id")
     * })
     */
    private $imputacion;

    /**
     * @var \BackendBundle\Entity\TblProveedores
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblProveedores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proveedor", referencedColumnName="id")
     * })
     */
    private $proveedor;

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
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return TblCompras
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set periodoMes
     *
     * @param integer $periodoMes
     *
     * @return TblCompras
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
     * @return TblCompras
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
     * @return TblCompras
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
     * @param string $nroComprobante
     *
     * @return TblCompras
     */
    public function setNroComprobante($nroComprobante)
    {
        $this->nroComprobante = $nroComprobante;

        return $this;
    }

    /**
     * Get nroComprobante
     *
     * @return string
     */
    public function getNroComprobante()
    {
        return $this->nroComprobante;
    }

    /**
     * Set cai
     *
     * @param integer $cai
     *
     * @return TblCompras
     */
    public function setCai($cai)
    {
        $this->cai = $cai;

        return $this;
    }

    /**
     * Get cai
     *
     * @return integer
     */
    public function getCai()
    {
        return $this->cai;
    }

    /**
     * Set neto105
     *
     * @param string $neto105
     *
     * @return TblCompras
     */
    public function setNeto105($neto105)
    {
        $this->neto105 = $neto105;

        return $this;
    }

    /**
     * Get neto105
     *
     * @return string
     */
    public function getNeto105()
    {
        return $this->neto105;
    }

    /**
     * Set neto21
     *
     * @param string $neto21
     *
     * @return TblCompras
     */
    public function setNeto21($neto21)
    {
        $this->neto21 = $neto21;

        return $this;
    }

    /**
     * Get neto21
     *
     * @return string
     */
    public function getNeto21()
    {
        return $this->neto21;
    }

    /**
     * Set neto27
     *
     * @param string $neto27
     *
     * @return TblCompras
     */
    public function setNeto27($neto27)
    {
        $this->neto27 = $neto27;

        return $this;
    }

    /**
     * Get neto27
     *
     * @return string
     */
    public function getNeto27()
    {
        return $this->neto27;
    }

    /**
     * Set iva105
     *
     * @param string $iva105
     *
     * @return TblCompras
     */
    public function setIva105($iva105)
    {
        $this->iva105 = $iva105;

        return $this;
    }

    /**
     * Get iva105
     *
     * @return string
     */
    public function getIva105()
    {
        return $this->iva105;
    }

    /**
     * Set iva21
     *
     * @param string $iva21
     *
     * @return TblCompras
     */
    public function setIva21($iva21)
    {
        $this->iva21 = $iva21;

        return $this;
    }

    /**
     * Get iva21
     *
     * @return string
     */
    public function getIva21()
    {
        return $this->iva21;
    }

    /**
     * Set iva27
     *
     * @param string $iva27
     *
     * @return TblCompras
     */
    public function setIva27($iva27)
    {
        $this->iva27 = $iva27;

        return $this;
    }

    /**
     * Get iva27
     *
     * @return string
     */
    public function getIva27()
    {
        return $this->iva27;
    }

    /**
     * Set nogravado
     *
     * @param string $nogravado
     *
     * @return TblCompras
     */
    public function setNogravado($nogravado)
    {
        $this->nogravado = $nogravado;

        return $this;
    }

    /**
     * Get nogravado
     *
     * @return string
     */
    public function getNogravado()
    {
        return $this->nogravado;
    }

    /**
     * Set exento
     *
     * @param string $exento
     *
     * @return TblCompras
     */
    public function setExento($exento)
    {
        $this->exento = $exento;

        return $this;
    }

    /**
     * Get exento
     *
     * @return string
     */
    public function getExento()
    {
        return $this->exento;
    }

    /**
     * Set percIva
     *
     * @param string $percIva
     *
     * @return TblCompras
     */
    public function setPercIva($percIva)
    {
        $this->percIva = $percIva;

        return $this;
    }

    /**
     * Get percIva
     *
     * @return string
     */
    public function getPercIva()
    {
        return $this->percIva;
    }

    /**
     * Set percIibb
     *
     * @param string $percIibb
     *
     * @return TblCompras
     */
    public function setPercIibb($percIibb)
    {
        $this->percIibb = $percIibb;

        return $this;
    }

    /**
     * Get percIibb
     *
     * @return string
     */
    public function getPercIibb()
    {
        return $this->percIibb;
    }

    /**
     * Set retGanancia
     *
     * @param string $retGanancia
     *
     * @return TblCompras
     */
    public function setRetGanancia($retGanancia)
    {
        $this->retGanancia = $retGanancia;

        return $this;
    }

    /**
     * Get retGanancia
     *
     * @return string
     */
    public function getRetGanancia()
    {
        return $this->retGanancia;
    }

    /**
     * Set total
     *
     * @param string $total
     *
     * @return TblCompras
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
     * Set codComprobante
     *
     * @param \BackendBundle\Entity\TblComprobantes $codComprobante
     *
     * @return TblCompras
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
     * Set empresa
     *
     * @param \BackendBundle\Entity\TblEmpresas $empresa
     *
     * @return TblCompras
     */
    public function setEmpresa(\BackendBundle\Entity\TblEmpresas $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \BackendBundle\Entity\TblEmpresas
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set imputacion
     *
     * @param \BackendBundle\Entity\TblImputaciones $imputacion
     *
     * @return TblCompras
     */
    public function setImputacion(\BackendBundle\Entity\TblImputaciones $imputacion = null)
    {
        $this->imputacion = $imputacion;

        return $this;
    }

    /**
     * Get imputacion
     *
     * @return \BackendBundle\Entity\TblImputaciones
     */
    public function getImputacion()
    {
        return $this->imputacion;
    }

    /**
     * Set proveedor
     *
     * @param \BackendBundle\Entity\TblProveedores $proveedor
     *
     * @return TblCompras
     */
    public function setProveedor(\BackendBundle\Entity\TblProveedores $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \BackendBundle\Entity\TblProveedores
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set tipoComprobante
     *
     * @param \BackendBundle\Entity\TblTiposComp $tipoComprobante
     *
     * @return TblCompras
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
