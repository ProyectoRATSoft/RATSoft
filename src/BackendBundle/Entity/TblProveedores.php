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


}

