<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recommendation
 *
 * @ORM\Table(name="recommendation", indexes={@ORM\Index(name="recommendationUser_idx", columns={"user_id"}), @ORM\Index(name="recommendationOffer_idx", columns={"offer_id"})})
 * @ORM\Entity(repositoryClass="Web\WebBundle\Entity\Repository\RecommendationRepository")
 */
class Recommendation
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
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="to_send", type="boolean", nullable=false)
     */
    private $toSend = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="recommended", type="boolean", nullable=false)
     */
    private $recommended;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="recommendations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Offer
     *
     * @ORM\ManyToOne(targetEntity="Offer", inversedBy="recommendations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="offer_id", referencedColumnName="id")
     * })
     */
    private $offer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="recommendation")
     * @ORM\JoinTable(name="recommendation_contacts",
     *   joinColumns={
     *     @ORM\JoinColumn(name="recommendation_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     *   }
     * )
     */
    private $contact;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreate = new \DateTime('now');
        $this->contact = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recommended = false;
    }


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
     * Set type
     *
     * @param string $type
     * @return Recommendation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set toSend
     *
     * @param int $toSend
     * @return Recommendation
     */
    public function setToSend($toSend)
    {
        $this->toSend = $toSend;

        return $this;
    }

    /**
     * Get toSend
     *
     * @return int
     */
    public function getToSend()
    {
        return $this->toSend;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Recommendation
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
     * Set recommended
     *
     * @param int $recommended
     * @return Recommendation
     */
    public function setRecommended($recommended)
    {
        $this->recommended = $recommended;

        return $this;
    }

    /**
     * Get recommended
     *
     * @return int
     */
    public function getRecommended()
    {
        return $this->recommended;
    }

    /**
     * Set user
     *
     * @param \Web\WebBundle\Entity\User $user
     * @return Recommendation
     */
    public function setUser(\Web\WebBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Web\WebBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set offer
     *
     * @param \Web\WebBundle\Entity\Offer $offer
     * @return Recommendation
     */
    public function setOffer(\Web\WebBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \Web\WebBundle\Entity\Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Add contact
     *
     * @param \Web\WebBundle\Entity\Contact $contact
     * @return Recommendation
     */
    public function addContact(\Web\WebBundle\Entity\Contact $contact)
    {
        $this->contact[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \Web\WebBundle\Entity\Contact $contact
     */
    public function removeContact(\Web\WebBundle\Entity\Contact $contact)
    {
        $this->contact->removeElement($contact);
    }

    /**
     * Get contact
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContact()
    {
        return $this->contact;
    }
}
