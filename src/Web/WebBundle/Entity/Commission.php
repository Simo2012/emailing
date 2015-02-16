<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commission
 *
 * @ORM\Table(name="commission", indexes={@ORM\Index(name="commissionRecommendation_idx", columns={"recommendation_id"}), @ORM\Index(name="commissionDatecreateRecommendation_idx", columns={"date_create", "recommendation_id"}), @ORM\Index(name="commissionContact_fk", columns={"contact_id"})})
 * @ORM\Entity
 */
class Commission
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
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

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
        $this->dateCreate = new \DateTime('now');
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
     * @return Commission
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
     * Set recommendation
     *
     * @param \Web\WebBundle\Entity\Recommendation $recommendation
     * @return Commission
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
     * @return Commission
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
