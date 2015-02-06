<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="userEmail_uk", columns={"email"})})
 * @ORM\Entity
 */
class User
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_login", type="datetime", nullable=false)
     */
    private $dateLogin;

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
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var boolean
     *
     * @ORM\Column(name="use_facebook", type="boolean", nullable=false)
     */
    private $useFacebook;

    /**
     * @var boolean
     *
     * @ORM\Column(name="use_twitter", type="boolean", nullable=false)
     */
    private $useTwitter;

    /**
     * @var boolean
     *
     * @ORM\Column(name="use_email", type="boolean", nullable=false)
     */
    private $useEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="bic", type="string", length=4096, nullable=false)
     */
    private $bic;

    /**
     * @var boolean
     *
     * @ORM\Column(name="optin_newsletter", type="boolean", nullable=false)
     */
    private $optinNewsletter;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_contacts", type="smallint", nullable=false)
     */
    private $nbContacts;

    /**
     * @var string
     *
     * @ORM\Column(name="available_amount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $availableAmount;



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
     * @return User
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
     * @return User
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
     * @return User
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
     * Set dateLogin
     *
     * @param \DateTime $dateLogin
     * @return User
     */
    public function setDateLogin($dateLogin)
    {
        $this->dateLogin = $dateLogin;

        return $this;
    }

    /**
     * Get dateLogin
     *
     * @return \DateTime 
     */
    public function getDateLogin()
    {
        return $this->dateLogin;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
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
     * @return User
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
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set active
     *
     * @param boolean $active
     * @return User
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
     * Set useFacebook
     *
     * @param boolean $useFacebook
     * @return User
     */
    public function setUseFacebook($useFacebook)
    {
        $this->useFacebook = $useFacebook;

        return $this;
    }

    /**
     * Get useFacebook
     *
     * @return boolean 
     */
    public function getUseFacebook()
    {
        return $this->useFacebook;
    }

    /**
     * Set useTwitter
     *
     * @param boolean $useTwitter
     * @return User
     */
    public function setUseTwitter($useTwitter)
    {
        $this->useTwitter = $useTwitter;

        return $this;
    }

    /**
     * Get useTwitter
     *
     * @return boolean 
     */
    public function getUseTwitter()
    {
        return $this->useTwitter;
    }

    /**
     * Set useEmail
     *
     * @param boolean $useEmail
     * @return User
     */
    public function setUseEmail($useEmail)
    {
        $this->useEmail = $useEmail;

        return $this;
    }

    /**
     * Get useEmail
     *
     * @return boolean 
     */
    public function getUseEmail()
    {
        return $this->useEmail;
    }

    /**
     * Set bic
     *
     * @param string $bic
     * @return User
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * Get bic
     *
     * @return string 
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Set optinNewsletter
     *
     * @param boolean $optinNewsletter
     * @return User
     */
    public function setOptinNewsletter($optinNewsletter)
    {
        $this->optinNewsletter = $optinNewsletter;

        return $this;
    }

    /**
     * Get optinNewsletter
     *
     * @return boolean 
     */
    public function getOptinNewsletter()
    {
        return $this->optinNewsletter;
    }

    /**
     * Set nbContacts
     *
     * @param integer $nbContacts
     * @return User
     */
    public function setNbContacts($nbContacts)
    {
        $this->nbContacts = $nbContacts;

        return $this;
    }

    /**
     * Get nbContacts
     *
     * @return integer 
     */
    public function getNbContacts()
    {
        return $this->nbContacts;
    }

    /**
     * Set availableAmount
     *
     * @param string $availableAmount
     * @return User
     */
    public function setAvailableAmount($availableAmount)
    {
        $this->availableAmount = $availableAmount;

        return $this;
    }

    /**
     * Get availableAmount
     *
     * @return string 
     */
    public function getAvailableAmount()
    {
        return $this->availableAmount;
    }
}
