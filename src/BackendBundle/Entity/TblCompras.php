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


}

