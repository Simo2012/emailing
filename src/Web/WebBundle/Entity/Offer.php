<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="offer", indexes={@ORM\Index(name="offerCountryActive_idx", columns={"country", "active", "category"}), @ORM\Index(name="offerSubsidiary_fk", columns={"subsidiary_id"})})
 * @ORM\Entity
 */
class Offer
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
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=false)
     */
    private $dateUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=4096, nullable=false)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=200, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="compensation", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $compensation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=false)
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime", nullable=false)
     */
    private $dateEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="advertiser_cookie", type="smallint", nullable=false)
     */
    private $advertiserCookie;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_cookie", type="smallint", nullable=false)
     */
    private $memberCookie;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", nullable=false)
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="platform_id", type="string", length=200, nullable=true)
     */
    private $platformId;

    /**
     * @var array
     *
     * @ORM\Column(name="category", type="simple_array", nullable=false)
     */
    private $category;

    /**
     * @var array
     *
     * @ORM\Column(name="publishing", type="simple_array", nullable=false)
     */
    private $publishing;

    /**
     * @var string
     *
     * @ORM\Column(name="rem_type", type="string", nullable=false)
     */
    private $remType;

    /**
     * @var string
     *
     * @ORM\Column(name="rem_advertiser", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $remAdvertiser;

    /**
     * @var integer
     *
     * @ORM\Column(name="rem_volume", type="integer", nullable=false)
     */
    private $remVolume;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rem_illimited", type="boolean", nullable=false)
     */
    private $remIllimited;

    /**
     * @var string
     *
     * @ORM\Column(name="rem_member", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $remMember;

    /**
     * @var \Subsidiary
     *
     * @ORM\ManyToOne(targetEntity="Web\WebBundle\Entity\Synchro\Subsidiary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subsidiary_id", referencedColumnName="id")
     * })
     */
    private $subsidiary;

    /**
     * @var \Brand
     *
     * @ORM\ManyToOne(targetEntity="Web\WebBundle\Entity\Synchro\Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;



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
     * Set country
     *
     * @param string $country
     * @return Offer
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Offer
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Offer
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Offer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Offer
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Offer
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set compensation
     *
     * @param string $compensation
     * @return Offer
     */
    public function setCompensation($compensation)
    {
        $this->compensation = $compensation;

        return $this;
    }

    /**
     * Get compensation
     *
     * @return string
     */
    public function getCompensation()
    {
        return $this->compensation;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Offer
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return Offer
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return Offer
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set advertiserCookie
     *
     * @param integer $advertiserCookie
     * @return Offer
     */
    public function setAdvertiserCookie($advertiserCookie)
    {
        $this->advertiserCookie = $advertiserCookie;

        return $this;
    }

    /**
     * Get advertiserCookie
     *
     * @return integer
     */
    public function getAdvertiserCookie()
    {
        return $this->advertiserCookie;
    }

    /**
     * Set memberCookie
     *
     * @param integer $memberCookie
     * @return Offer
     */
    public function setMemberCookie($memberCookie)
    {
        $this->memberCookie = $memberCookie;

        return $this;
    }

    /**
     * Get memberCookie
     *
     * @return integer
     */
    public function getMemberCookie()
    {
        return $this->memberCookie;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return Offer
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set platformId
     *
     * @param string $platformId
     * @return Offer
     */
    public function setPlatformId($platformId)
    {
        $this->platformId = $platformId;

        return $this;
    }

    /**
     * Get platformId
     *
     * @return string
     */
    public function getPlatformId()
    {
        return $this->platformId;
    }

    /**
     * Set category
     *
     * @param array $category
     * @return Offer
     */
    public function setCategory(array $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return array
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set publishing
     *
     * @param array $publishing
     * @return Offer
     */
    public function setPublishing(array $publishing)
    {
        $this->publishing = $publishing;

        return $this;
    }

    /**
     * Get publishing
     *
     * @return array
     */
    public function getPublishing()
    {
        return $this->publishing;
    }

    /**
     * Set remType
     *
     * @param string $remType
     * @return Offer
     */
    public function setRemType($remType)
    {
        $this->remType = $remType;

        return $this;
    }

    /**
     * Get remType
     *
     * @return string
     */
    public function getRemType()
    {
        return $this->remType;
    }

    /**
     * Set remAdvertiser
     *
     * @param string $remAdvertiser
     * @return Offer
     */
    public function setRemAdvertiser($remAdvertiser)
    {
        $this->remAdvertiser = $remAdvertiser;

        return $this;
    }

    /**
     * Get remAdvertiser
     *
     * @return string
     */
    public function getRemAdvertiser()
    {
        return $this->remAdvertiser;
    }

    /**
     * Set remVolume
     *
     * @param integer $remVolume
     * @return Offer
     */
    public function setRemVolume($remVolume)
    {
        $this->remVolume = $remVolume;

        return $this;
    }

    /**
     * Get remVolume
     *
     * @return integer
     */
    public function getRemVolume()
    {
        return $this->remVolume;
    }

    /**
     * Set remIllimited
     *
     * @param boolean $remIllimited
     * @return Offer
     */
    public function setRemIllimited($remIllimited)
    {
        $this->remIllimited = $remIllimited;

        return $this;
    }

    /**
     * Get remIllimited
     *
     * @return boolean
     */
    public function getRemIllimited()
    {
        return $this->remIllimited;
    }

    /**
     * Set remMember
     *
     * @param string $remMember
     * @return Offer
     */
    public function setRemMember($remMember)
    {
        $this->remMember = $remMember;

        return $this;
    }

    /**
     * Get remMember
     *
     * @return string
     */
    public function getRemMember()
    {
        return $this->remMember;
    }

    /**
     * Set subsidiary
     *
     * @param \Web\WebBundle\Entity\Synchro\Subsidiary $subsidiary
     * @return Offer
     */
    public function setSubsidiary(\Web\WebBundle\Entity\Synchro\Subsidiary $subsidiary = null)
    {
        $this->subsidiary = $subsidiary;

        return $this;
    }

    /**
     * Get subsidiary
     *
     * @return \Web\WebBundle\Entity\Synchro\Subsidiary
     */
    public function getSubsidiary()
    {
        return $this->subsidiary;
    }

    /**
     * Set brand
     *
     * @param \Web\WebBundle\Entity\Synchro\Brand $brand
     * @return Offer
     */
    public function setBrand(\Web\WebBundle\Entity\Synchro\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Web\WebBundle\Entity\Synchro\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }
}
