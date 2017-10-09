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
     * Set tipoComp
     *
     * @param string $tipoComp
     *
     * @return TblTiposComp
     */
    public function setTipoComp($tipoComp)
    {
        $this->tipoComp = $tipoComp;

        return $this;
    }

    /**
     * Get tipoComp
     *
     * @return string
     */
    public function getTipoComp()
    {
        return $this->tipoComp;
    }

    /**
     * Set codComp
     *
     * @param \BackendBundle\Entity\TblComprobantes $codComp
     *
     * @return TblTiposComp
     */
    public function setCodComp(\BackendBundle\Entity\TblComprobantes $codComp = null)
    {
        $this->codComp = $codComp;

        return $this;
    }

    /**
     * Get codComp
     *
     * @return \BackendBundle\Entity\TblComprobantes
     */
    public function getCodComp()
    {
        return $this->codComp;
    }
}
