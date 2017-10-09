<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblImputProv
 *
 * @ORM\Table(name="tbl_imput_prov", indexes={@ORM\Index(name="FK_IMPUT_idx", columns={"id_imp"})})
 * @ORM\Entity
 */
class TblImputProv
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
     * @ORM\Column(name="id_prov", type="integer", nullable=false)
     */
    private $idProv;

    /**
     * @var \BackendBundle\Entity\TblImputaciones
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\TblImputaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_imp", referencedColumnName="id")
     * })
     */
    private $idImp;


}

