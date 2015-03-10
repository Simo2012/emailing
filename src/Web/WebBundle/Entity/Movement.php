<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movement
 *
 * @ORM\Table(name="movement", indexes={@ORM\Index(name="movementUser_fk", columns={"user_id"}), @ORM\Index(name="movementPaymentrequest_fk", columns={"payment_request_id"}), @ORM\Index(name="movementCommission_fk", columns={"commission_id"})})
 * @ORM\Entity(repositoryClass="Web\WebBundle\Entity\Repository\MovementRepository")
 */
class Movement
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
     * @ORM\Column(name="movement_name", type="string", length=20, nullable=false)
     */
    private $movementName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_movement", type="datetime", nullable=false)
     */
    private $dateMovement;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_movement", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $amountMovement;

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
     * @var \PaymentRequest
     *
     * @ORM\ManyToOne(targetEntity="PaymentRequest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_request_id", referencedColumnName="id")
     * })
     */
    private $paymentRequest;

    /**
     * @var \Commission
     *
     * @ORM\ManyToOne(targetEntity="Commission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commission_id", referencedColumnName="id")
     * })
     */
    private $commission;



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
     * Set movementName
     *
     * @param string $movementName
     * @return Movement
     */
    public function setMovementName($movementName)
    {
        $this->movementName = $movementName;

        return $this;
    }

    /**
     * Get movementName
     *
     * @return string 
     */
    public function getMovementName()
    {
        return $this->movementName;
    }

    /**
     * Set dateMovement
     *
     * @param \DateTime $dateMovement
     * @return Movement
     */
    public function setDateMovement($dateMovement)
    {
        $this->dateMovement = $dateMovement;

        return $this;
    }

    /**
     * Get dateMovement
     *
     * @return \DateTime 
     */
    public function getDateMovement()
    {
        return $this->dateMovement;
    }

    /**
     * Set amountMovement
     *
     * @param string $amountMovement
     * @return Movement
     */
    public function setAmountMovement($amountMovement)
    {
        $this->amountMovement = $amountMovement;

        return $this;
    }

    /**
     * Get amountMovement
     *
     * @return string 
     */
    public function getAmountMovement()
    {
        return $this->amountMovement;
    }

    /**
     * Set user
     *
     * @param \Web\WebBundle\Entity\User $user
     * @return Movement
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
     * Set paymentRequest
     *
     * @param \Web\WebBundle\Entity\PaymentRequest $paymentRequest
     * @return Movement
     */
    public function setPaymentRequest(\Web\WebBundle\Entity\PaymentRequest $paymentRequest = null)
    {
        $this->paymentRequest = $paymentRequest;

        return $this;
    }

    /**
     * Get paymentRequest
     *
     * @return \Web\WebBundle\Entity\PaymentRequest 
     */
    public function getPaymentRequest()
    {
        return $this->paymentRequest;
    }

    /**
     * Set commission
     *
     * @param \Web\WebBundle\Entity\Commission $commission
     * @return Movement
     */
    public function setCommission(\Web\WebBundle\Entity\Commission $commission = null)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return \Web\WebBundle\Entity\Commission 
     */
    public function getCommission()
    {
        return $this->commission;
    }
}
