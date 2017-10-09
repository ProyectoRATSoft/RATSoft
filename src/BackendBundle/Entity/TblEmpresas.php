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


}

