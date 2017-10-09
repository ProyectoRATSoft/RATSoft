<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblSituacionIva
 *
 * @ORM\Table(name="tbl_situacion_iva")
 * @ORM\Entity
 */
class TblSituacionIva
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
     * @ORM\Column(name="codigo", type="string", length=45, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="string", length=45, nullable=true)
     */
    private $detalle;


}

