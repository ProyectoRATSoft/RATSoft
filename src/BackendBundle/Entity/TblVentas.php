<?php

namespace BackendBundle\Entity;

/**
 * TblVentas
 */
class TblVentas
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $periodoMes;

    /**
     * @var integer
     */
    private $periodoAno;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var integer
     */
    private $nroComprobante;

    /**
     * @var string
     */
    private $netoReventa;

    /**
     * @var string
     */
    private $netoFabric;

    /**
     * @var string
     */
    private $netoExento;

    /**
     * @var string
     */
    private $ivaRi;

    /**
     * @var string
     */
    private $ivaRni;

    /**
     * @var string
     */
    private $ivaSnc;

    /**
     * @var string
     */
    private $ivaMon;

    /**
     * @var string
     */
    private $ivaCf;

    /**
     * @var string
     */
    private $ivaExento;

    /**
     * @var string
     */
    private $retencion;

    /**
     * @var string
     */
    private $percepcion;

    /**
     * @var string
     */
    private $total;

    /**
     * @var \BackendBundle\Entity\TblProveedores
     */
    private $cliente;

    /**
     * @var \BackendBundle\Entity\TblComprobantes
     */
    private $codComprobante;

    /**
     * @var \BackendBundle\Entity\TblTiposComp
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
    /**
     * @var string
     */
    private $neto105;

    /**
     * @var string
     */
    private $neto21;

    /**
     * @var string
     */
    private $iva105;

    /**
     * @var string
     */
    private $iva21;

    /**
     * @var string
     */
    private $retGan;


    /**
     * Set neto105
     *
     * @param string $neto105
     *
     * @return TblVentas
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
     * @return TblVentas
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
     * Set iva105
     *
     * @param string $iva105
     *
     * @return TblVentas
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
     * @return TblVentas
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
     * Set retGan
     *
     * @param string $retGan
     *
     * @return TblVentas
     */
    public function setRetGan($retGan)
    {
        $this->retGan = $retGan;

        return $this;
    }

    /**
     * Get retGan
     *
     * @return string
     */
    public function getRetGan()
    {
        return $this->retGan;
    }
    /**
     * @var \BackendBundle\Entity\TblEmpresas
     */
    private $empresa;


    /**
     * Set empresa
     *
     * @param \BackendBundle\Entity\TblEmpresas $empresa
     *
     * @return TblVentas
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
     * @var \DateTime
     */
    private $fechaIngreso;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $usuario;


    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return TblVentas
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
     * Set usuario
     *
     * @param \BackendBundle\Entity\User $usuario
     *
     * @return TblVentas
     */
    public function setUsuario(\BackendBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    /**
     * @var integer
     */
    private $activo;


    /**
     * Set activo
     *
     * @param integer $activo
     *
     * @return TblVentas
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
}
