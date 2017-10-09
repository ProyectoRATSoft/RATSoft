<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblTiposComp
 *
 * @ORM\Table(name="tbl_tipos_comp", indexes={@ORM\Index(name="FK_COD_COMP_idx", columns={"cod_comp"})})
 * @ORM\Entity
 */
class TblTiposComp
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
     * @ORM\Column(name="tipo_comp", type="string", length=3, nullable=false)
     */
    private $tipoComp;

    /**
     * @var \BackendBundle\Entity\TblComprobantes
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblComprobantes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cod_comp", referencedColumnName="id")
     * })
     */
    private $codComp;


}

