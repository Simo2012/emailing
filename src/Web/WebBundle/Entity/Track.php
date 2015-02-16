<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Track
 *
 * @ORM\Table(name="track", uniqueConstraints={@ORM\UniqueConstraint(name="recommendation_id", columns={"recommendation_id", "ip_address"})}, indexes={@ORM\Index(name="date_update", columns={"date_update", "recommendation_id"}), @ORM\Index(name="trackContact_fk", columns={"contact_id"}), @ORM\Index(name="IDX_D6E3F8A6D173940B", columns={"recommendation_id"})})
 * @ORM\Entity
 */
class Track
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_address", type="integer", nullable=false)
     */
    private $ipAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_open", type="datetime", nullable=true)
     */
    private $dateOpen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_click", type="datetime", nullable=true)
     */
    private $dateClick;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sale", type="datetime", nullable=true)
     */
    private $dateSale;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $saleAmount;

    /**
     * @var \Recommendation
     *
     * @ORM\ManyToOne(targetEntity="Recommendation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recommendation_id", referencedColumnName="id")
     * })
     */
    private $recommendation;

    /**
     * @var \Contact
     *
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     * })
     */
    private $contact;



    /**
     * Constructeur, valeurs par défaut
     */
    public function __construct()
    {
        $loNow = new \DateTime('now');
        $this->dateCreate = $loNow;
        $this->dateUpdate = $loNow;
    } // __construct

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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Track
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
     * @return Track
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
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return Track
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return integer
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set dateOpen
     *
     * @param \DateTime $dateOpen
     * @return Track
     */
    public function setDateOpen($dateOpen)
    {
        $this->dateOpen = $dateOpen;

        return $this;
    }

    /**
     * Get dateOpen
     *
     * @return \DateTime
     */
    public function getDateOpen()
    {
        return $this->dateOpen;
    }

    /**
     * Set dateClick
     *
     * @param \DateTime $dateClick
     * @return Track
     */
    public function setDateClick($dateClick)
    {
        $this->dateClick = $dateClick;

        return $this;
    }

    /**
     * Get dateClick
     *
     * @return \DateTime
     */
    public function getDateClick()
    {
        return $this->dateClick;
    }

    /**
     * Set dateSale
     *
     * @param \DateTime $dateSale
     * @return Track
     */
    public function setDateSale($dateSale)
    {
        $this->dateSale = $dateSale;

        return $this;
    }

    /**
     * Get dateSale
     *
     * @return \DateTime
     */
    public function getDateSale()
    {
        return $this->dateSale;
    }

    /**
     * Set saleAmount
     *
     * @param string $saleAmount
     * @return Track
     */
    public function setSaleAmount($saleAmount)
    {
        $this->saleAmount = $saleAmount;

        return $this;
    }

    /**
     * Get saleAmount
     *
     * @return string
     */
    public function getSaleAmount()
    {
        return $this->saleAmount;
    }

    /**
     * Set recommendation
     *
     * @param \Web\WebBundle\Entity\Recommendation $recommendation
     * @return Track
     */
    public function setRecommendation(\Web\WebBundle\Entity\Recommendation $recommendation = null)
    {
        $this->recommendation = $recommendation;

        return $this;
    }

    /**
     * Get recommendation
     *
     * @return \Web\WebBundle\Entity\Recommendation
     */
    public function getRecommendation()
    {
        return $this->recommendation;
    }

    /**
     * Set contact
     *
     * @param \Web\WebBundle\Entity\Contact $contact
     * @return Track
     */
    public function setContact(\Web\WebBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \Web\WebBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }
}
