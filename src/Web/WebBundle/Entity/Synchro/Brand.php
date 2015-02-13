<?php

namespace Web\WebBundle\Entity\Synchro;

use Doctrine\ORM\Mapping as ORM;

/**
 * Brand
 *
 * @ORM\Table(name="SYNCHRO.brand", indexes={@ORM\Index(name="brandAdvertiserId_fk", columns={"advertiserid"}), @ORM\Index(name="brandCountryId_fk", columns={"countryId"}), @ORM\Index(name="brandSubsidiaryId_fk", columns={"subsidiaryId"})})
 * @ORM\Entity
 */
class Brand
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", nullable=true)
     */
    private $state;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rubizz", type="boolean", nullable=false)
     */
    private $rubizz;

    /**
     * @var string
     *
     * @ORM\Column(name="stateRubizz", type="string", nullable=false)
     */
    private $staterubizz;

    /**
     * @var \Advertiser
     *
     * @ORM\ManyToOne(targetEntity="Advertiser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="advertiserid", referencedColumnName="id")
     * })
     */
    private $advertiserid;

    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="countryId", referencedColumnName="id")
     * })
     */
    private $countryid;

    /**
     * @var \Subsidiary
     *
     * @ORM\ManyToOne(targetEntity="Subsidiary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subsidiaryId", referencedColumnName="id")
     * })
     */
    private $subsidiaryid;



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
     * Set name
     *
     * @param string $name
     * @return Brand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Brand
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set rubizz
     *
     * @param boolean $rubizz
     * @return Brand
     */
    public function setRubizz($rubizz)
    {
        $this->rubizz = $rubizz;

        return $this;
    }

    /**
     * Get rubizz
     *
     * @return boolean
     */
    public function getRubizz()
    {
        return $this->rubizz;
    }

    /**
     * Set staterubizz
     *
     * @param string $staterubizz
     * @return Brand
     */
    public function setStaterubizz($staterubizz)
    {
        $this->staterubizz = $staterubizz;

        return $this;
    }

    /**
     * Get staterubizz
     *
     * @return string
     */
    public function getStaterubizz()
    {
        return $this->staterubizz;
    }

    /**
     * Set advertiserid
     *
     * @param Advertiser $advertiserid
     * @return Brand
     */
    public function setAdvertiserid(Advertiser $advertiserid = null)
    {
        $this->advertiserid = $advertiserid;

        return $this;
    }

    /**
     * Get advertiserid
     *
     * @return Advertiser
     */
    public function getAdvertiserid()
    {
        return $this->advertiserid;
    }

    /**
     * Set countryid
     *
     * @param Country $countryid
     * @return Brand
     */
    public function setCountryid(Country $countryid = null)
    {
        $this->countryid = $countryid;

        return $this;
    }

    /**
     * Get countryid
     *
     * @return Country
     */
    public function getCountryid()
    {
        return $this->countryid;
    }

    /**
     * Set subsidiaryid
     *
     * @param Subsidiary $subsidiaryid
     * @return Brand
     */
    public function setSubsidiaryid(Subsidiary $subsidiaryid = null)
    {
        $this->subsidiaryid = $subsidiaryid;

        return $this;
    }

    /**
     * Get subsidiaryid
     *
     * @return Subsidiary
     */
    public function getSubsidiaryid()
    {
        return $this->subsidiaryid;
    }
}
