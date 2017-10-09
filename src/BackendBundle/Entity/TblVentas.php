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


}

