<?php

namespace Web\WebBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact", uniqueConstraints={@ORM\UniqueConstraint(name="contactUserEmail_uk", columns={"user_id", "email"})}, indexes={@ORM\Index(name="IDX_4C62E638A76ED395", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Web\WebBundle\Entity\Repository\ContactRepository")
 */
class Contact
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=false)
     */
    private $dateUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=100, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="subscriber", type="boolean", nullable=false)
     */
    private $subscriber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="direct_unsubscribe", type="boolean", nullable=false)
     */
    private $directUnsubscribe;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var collection
     *
     * @ORM\OneToMany(targetEntity="Commission", mappedBy="contact")
     **/
    private $commissions;

    public function __construct() {
        $loNow = new \DateTime();
        $this->dateCreate           = $loNow;
        $this->dateUpdate           = $loNow;
        $this->subscriber           = true;
        $this->directUnsubscribe    = false;
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
     * @return Contact
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
     * @return Contact
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
     * Set firstname
     *
     * @param string $firstname
     * @return Contact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set subscriber
     *
     * @param boolean $subscriber
     * @return Contact
     */
    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * Get subscriber
     *
     * @return boolean
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * Set directUnsubscribe
     *
     * @param boolean $directUnsubscribe
     * @return Contact
     */
    public function setDirectUnsubscribe($directUnsubscribe)
    {
        $this->directUnsubscribe = $directUnsubscribe;

        return $this;
    }

    /**
     * Get directUnsubscribe
     *
     * @return boolean
     */
    public function getDirectUnsubscribe()
    {
        return $this->directUnsubscribe;
    }

    /**
     * Set user
     *
     * @param \Web\WebBundle\Entity\User $user
     * @return Contact
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
}
