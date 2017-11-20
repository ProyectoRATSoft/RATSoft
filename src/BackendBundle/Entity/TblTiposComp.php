<?php

namespace BackendBundle\Entity;

/**
 * TblTiposComp
 */
class TblTiposComp
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tipoComp;

    /**
     * @var \BackendBundle\Entity\TblComprobantes
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
    /**
     * @var integer
     */
    private $blkExe;

    /**
     * @var integer
     */
    private $blkPerciva;

    /**
     * @var integer
     */
    private $blkPerciibb;

    /**
     * @var integer
     */
    private $blkRet;

    /**
     * @var integer
     */
    private $blkNetos;

    /**
     * @var integer
     */
    private $blkIva;

    /**
     * @var integer
     */
    private $blkTotal;

    /**
     * @var integer
     */
    private $blkNograv;

    /**
     * @var integer
     */
    private $autoiva;

    /**
     * @var integer
     */
    private $autoneto;

    /**
     * @var integer
     */
    private $autototal;


    /**
     * Set blkExe
     *
     * @param integer $blkExe
     *
     * @return TblTiposComp
     */
    public function setBlkExe($blkExe)
    {
        $this->blkExe = $blkExe;

        return $this;
    }

    /**
     * Get blkExe
     *
     * @return integer
     */
    public function getBlkExe()
    {
        return $this->blkExe;
    }

    /**
     * Set blkPerciva
     *
     * @param integer $blkPerciva
     *
     * @return TblTiposComp
     */
    public function setBlkPerciva($blkPerciva)
    {
        $this->blkPerciva = $blkPerciva;

        return $this;
    }

    /**
     * Get blkPerciva
     *
     * @return integer
     */
    public function getBlkPerciva()
    {
        return $this->blkPerciva;
    }

    /**
     * Set blkPerciibb
     *
     * @param integer $blkPerciibb
     *
     * @return TblTiposComp
     */
    public function setBlkPerciibb($blkPerciibb)
    {
        $this->blkPerciibb = $blkPerciibb;

        return $this;
    }

    /**
     * Get blkPerciibb
     *
     * @return integer
     */
    public function getBlkPerciibb()
    {
        return $this->blkPerciibb;
    }

    /**
     * Set blkRet
     *
     * @param integer $blkRet
     *
     * @return TblTiposComp
     */
    public function setBlkRet($blkRet)
    {
        $this->blkRet = $blkRet;

        return $this;
    }

    /**
     * Get blkRet
     *
     * @return integer
     */
    public function getBlkRet()
    {
        return $this->blkRet;
    }

    /**
     * Set blkNetos
     *
     * @param integer $blkNetos
     *
     * @return TblTiposComp
     */
    public function setBlkNetos($blkNetos)
    {
        $this->blkNetos = $blkNetos;

        return $this;
    }

    /**
     * Get blkNetos
     *
     * @return integer
     */
    public function getBlkNetos()
    {
        return $this->blkNetos;
    }

    /**
     * Set blkIva
     *
     * @param integer $blkIva
     *
     * @return TblTiposComp
     */
    public function setBlkIva($blkIva)
    {
        $this->blkIva = $blkIva;

        return $this;
    }

    /**
     * Get blkIva
     *
     * @return integer
     */
    public function getBlkIva()
    {
        return $this->blkIva;
    }

    /**
     * Set blkTotal
     *
     * @param integer $blkTotal
     *
     * @return TblTiposComp
     */
    public function setBlkTotal($blkTotal)
    {
        $this->blkTotal = $blkTotal;

        return $this;
    }

    /**
     * Get blkTotal
     *
     * @return integer
     */
    public function getBlkTotal()
    {
        return $this->blkTotal;
    }

    /**
     * Set blkNograv
     *
     * @param integer $blkNograv
     *
     * @return TblTiposComp
     */
    public function setBlkNograv($blkNograv)
    {
        $this->blkNograv = $blkNograv;

        return $this;
    }

    /**
     * Get blkNograv
     *
     * @return integer
     */
    public function getBlkNograv()
    {
        return $this->blkNograv;
    }

    /**
     * Set autoiva
     *
     * @param integer $autoiva
     *
     * @return TblTiposComp
     */
    public function setAutoiva($autoiva)
    {
        $this->autoiva = $autoiva;

        return $this;
    }

    /**
     * Get autoiva
     *
     * @return integer
     */
    public function getAutoiva()
    {
        return $this->autoiva;
    }

    /**
     * Set autoneto
     *
     * @param integer $autoneto
     *
     * @return TblTiposComp
     */
    public function setAutoneto($autoneto)
    {
        $this->autoneto = $autoneto;

        return $this;
    }

    /**
     * Get autoneto
     *
     * @return integer
     */
    public function getAutoneto()
    {
        return $this->autoneto;
    }

    /**
     * Set autototal
     *
     * @param integer $autototal
     *
     * @return TblTiposComp
     */
    public function setAutototal($autototal)
    {
        $this->autototal = $autototal;

        return $this;
    }

    /**
     * Get autototal
     *
     * @return integer
     */
    public function getAutototal()
    {
        return $this->autototal;
    }
}
