<?php

namespace Web\WebBundle\Entity\Synchro;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advertiser
 *
 * @ORM\Table(name="SYNCHRO.advertiser", indexes={@ORM\Index(name="advertiserActive_idx", columns={"type", "active", "rubizz", "validated", "name"})})
 * @ORM\Entity
 */
class Advertiser
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
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rubizz", type="boolean", nullable=false)
     */
    private $rubizz;

    /**
     * @var boolean
     *
     * @ORM\Column(name="validated", type="boolean", nullable=false)
     */
    private $validated;



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
     * @return Advertiser
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
     * Set type
     *
     * @param string $type
     * @return Advertiser
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
     * Set active
     *
     * @param boolean $active
     * @return Advertiser
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
     * Set rubizz
     *
     * @param boolean $rubizz
     * @return Advertiser
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
     * Set validated
     *
     * @param boolean $validated
     * @return Advertiser
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean
     */
    public function getValidated()
    {
        return $this->validated;
    }
}
