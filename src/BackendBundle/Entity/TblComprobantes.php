<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblComprobantes
 *
 * @ORM\Table(name="tbl_comprobantes")
 * @ORM\Entity
 */
class TblComprobantes
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
     * @ORM\Column(name="codigo", type="string", length=4, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="string", length=45, nullable=true)
     */
    private $detalle;


}

